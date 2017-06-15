<?php
namespace softr\MakoQueue;

use \Exception;
use \ArrayObject;

use mako\syringe\Container;
use mako\database\ConnectionManager;

use softr\MakoQueue\Job;
use softr\MakoQueue\Queue;

/**
 * QueueManager
 *
 * @author     Agência Softr Ltda
 * @copyright  (c) 2017
 */
class QueueManager
{
    /**
     * Container Instance.
     *
     * @var \mako\syringe\Container
     */
    protected $container;

    /**
     * Database connection.
     *
     * @var \mako\database\Connection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param  Container          $container   Container instance
     * @param  ConnectionManager  $connection  Connection Manager instance
     */
    public function __construct(Container $container, ConnectionManager $connection)
    {
        $this->container = $container;

        $this->connection = $connection;
    }

    /**
     * Return queues list
     *
     * @return  ArrayObject  Queues array
     */
    public function getQueues()
    {
        $queues = [];

        foreach ($this->table()->select(['queue'])->distinct()->all() as $row) {
            $queues[] = $this->getQueue($row->queue);
        }

        return new ArrayObject($queues);
    }

    /**
     * Return new queue instance
     *
     * @param   string   $name     Queue name
     * @return  Queue
     */
    public function getQueue($name)
    {
        return new Queue($this, $name);
    }

    /**
     * Return queue jobs array
     *
     * @param   string   $name     Queue name
     * @param   int      $max      Queue max records
     * @return  Queue
     */
    public function getQueueJobs(Queue $queue, $max = 50)
    {
        $jobs = [];

        $query = $this->table()->orderBy('failed_at');

        if ($max > 0) {
            $query->limit($max);
        }

        foreach ($query->all() as $record) {
            $job = $this->container->get($record->class, [json_decode($record->data, true)]);

            $job->setId($record->id);

            $jobs[] = $job;
        }

        return new ArrayObject($jobs);
    }

    /**
     * Clear all queues
     *
     * @return  boolean
     */
    public function clearQueues()
    {
        return $this->table()->where('id', '>', '0')->delete();
    }

    /**
     * Clear queue jobs
     *
     * @return  boolean
     */
    public function clearQueue($name)
    {
        return $this->table()->where('queue', '=', $name)->delete();
    }

    /**
     * Execute jobs in queue
     *
     * @param   Queue   $queue  Queue instance
     * @param   int     $max    Max records
     * @return  bool
     */
    public function executeQueue(Queue $queue, $max = 50)
    {
        foreach($this->getQueueJobs($queue, $max) as $job) {
            try {
                $this->container->call([$job, 'execute']);

                $this->deleteJob($job);

                return true;
            } catch(Exception $e) {
                $this->failJob($job, $e);
            }
        }
    }

    /**
     * Execute all jobs
     *
     * @return  bool
     */
    public function executeAll()
    {
        foreach ($this->getQueues() as $queue) {
            $this->executeQueue($queue, 0);
        }
    }

    /**
     * Add new job to queue
     *
     * @param  Job     $job    Job instance
     * @param  string  $queue  Queue name
     * @return bool
     */
    public function addJob(Job $job, $queue)
    {
        return $this->table()->insert([
            'queue'      => $queue,
            'data'       => json_encode($job->getData()),
            'class'      => get_class($job),
            'created_at' => time()
        ]);
    }

    /**
     * Remove job from queue
     *
     * @param  Job   $job  Job instance
     * @return bool
     */
    public function deleteJob(Job $job)
    {
        return $this->table()->where('id', '=', $job->getId())->delete();
    }

    /**
     * Saves error job on execution failure
     *
     * @param   Job        $job  Job instance
     * @param   Exception  $e    Exception instance
     * @return  void
     */
    public function failJob(Job $job, Exception $e)
    {
        return $this->table()->where('id', '=', $job->getId())->update(['log' => $e->getMessage(), 'failed_at' => time()]);
    }

    /**
     * Returns a query builder instance.
     *
     * @access  private
     * @return  \mako\database\query\Query
     */
    private function table()
    {
        return $this->connection->builder()->table('mako_queues');
    }
}