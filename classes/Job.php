<?php
namespace softr\MakoQueue;

/**
 * Job Abstract Class
 *
 * @author     Agência Softr Ltda
 * @copyright  (c) 2017
 */
abstract class Job
{
    /**
     * The job Id.
     *
     * @var array
     */
    protected $id = null;

    /**
     * The job data.
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor
     *
     * @param  mixed  $data  Job Data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Sets the The job Id.
     *
     * @param  int  $id  The Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Sets the The job data.
     *
     * @param  array  $data  The data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets the The job Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the The job data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}