<?php

namespace RobinSchedule;

use RobinSchedule\Contract\Job as JobContract;

/**
 * Class RobinSchedule
 * @author Me
 */
class TimeEvenJob implements JobContract
{
    /*
     *默认同步
     */
    protected $type = self::ASYNC;
    protected $delay = -300;
    protected $at = '2001-01-01 12:00:00';

    protected $queueName = 'queued-job';

    public $args = [];

    protected $track = false;

	public function withPayload($args = [])
	{
		$this->args = $args;
		return $this;
	}	

    public function dispatch(\RobinSchedule\TimeEven $instance)
    {
        if($this->type == self::SYNC){
            $instance->enqueueJob($this->queueName, $this, $this->args, $this->track);
        }else if($this->type == self::ASYNC && $this->delay){
            $instance->enqueueJobIn($this->delay, $this->queueName, $this, $this->args);
        }else if($this->type == self::ASYNC && $this->at){
            $instance->enqueueJobAt($this->at, $this->queueName, $this, $this->args);
        }

    }

	/**
	 * 执行任务的前置条件
	 */
	public function setUp()
	{
	}

	public function perform()
	{
        throw new TimeEvenException("please implement perform method");
	}

	/**
	 * 执行任务的后置条件
	 */
	public function tearDown()
	{
	}


}
