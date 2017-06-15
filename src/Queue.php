<?php
namespace softr\MakoQueue;

use softr\MakoQueue\Job;
use softr\MakoQueue\QueueManager;

/**
 * Queue
 *
 * @author     Agência Softr Ltda
 * @copyright  (c) 2017
 */
class Queue
{
    /**
     * Queue name
     *
     * @var string
     */
    protected $name;

    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param  QueueManager  $manager  Queue manager instance
     * @param  string        $name     Queue name
     */
    public function __construct(QueueManager $manager, $name = 'UNIVERSAL')
    {
        $this->manager = $manager;

        $this->name = $name;
    }

    /**
     * Return Queue manager
     *
     * @return QueueManager
     */
    private function getManager()
    {
        return $this->manager;
    }

    /**
     * Return queue id
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return Queue Jobs
     *
     * @return  array
     */
    public function getJobs($max = 50)
    {
        return $this->getManager()->getQueueJobs($this, $max);
    }

    /**
     * Add Job to Queue
     *
     * @param  Job  $job  [description]
     */
    public function addJob(Job $job)
    {
        return $this->getManager()->addJob($job, $this->getName());
    }

    /**
     * Delete Job from Queue
     *
     * @param  Job  $job  Job Instance
     */
    public function deleteJob(Job $job)
    {
        return $this->getManager()->deleteJob($job, $this->getName());
    }

    /**
     * Executes $max jobs from queue
     *
     * @param  int  $max      Max queue records
     */
    public function execute($max = 50)
    {
        $this->getManager()->executeQueue($this, $max);
    }
}