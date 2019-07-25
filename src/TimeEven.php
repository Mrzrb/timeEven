<?php

namespace RobinSchedule;

use Psr\Container\ContainerInterface;

use Resque;
use Resque_Worker;
use ResqueScheduler_Worker;
use Resque_Job_Status;
use ResqueScheduler;
use RobinSchedule\Contract\Job;

class TimeEven{
    
    private $app;
    public function __construct(ContainerInterface $app = null, $config = [])
    {
        $this->app = $app;
        $dns = sprintf("%s:%s", $config['host'], $config['port']);
        Resque::setBackend($dns);
    }

    public function enqueueJob($queueName, Job $job, $args, $track = false)
    {
        $reflecClass = new \ReflectionClass($job);
        Resque::enqueue($queueName, $reflecClass->getName(), $args, $track);
    }

	public function enqueueJobIn($in, $queue, Job $job, $args = array())
	{
        $reflecClass = new \ReflectionClass($job);
		ResqueScheduler::enqueueIn($in, $queue, $reflecClass->getName(), $args);
	}

	public function enqueueJobAt($in, $queue, Job $job, $args = array())
	{
        $reflecClass = new \ReflectionClass($job);
		ResqueScheduler::enqueueAt($in, $queue, $reflecClass->getName(), $args);
	}

	/**
	 * 启动 worker
	 *
	 * @param $queue_name string 队列名称
	 * @param $count integer worker个数，默认1个
	 * @param $interval integer 间隔秒数，默认5秒
	 * @param $prefix string Redis命名空间(前缀)，默认: resque
	 */
	public function work($queue_name = "*", $count = 1, $interval = 5, $prefix = null)
	{

		for ($i = 0; $i < $count; ++$i) {
			$pid = pcntl_fork();
			if ($pid === false || $pid === -1) {
				die();
			} else if (!$pid) {
				// Child, start the worker
				$queues = explode(',', $queue_name);
				$worker = new Resque_Worker($queues);
				$worker->work($interval);
				break;
			}
		}
	}
    
	/**
	 * 启动计划任务消费的 Worker
	 *
	 * ./yii resque/start-schedule >> /var/log/resque/schedule.log &
	 */
	public function schedule()
	{
		$pid = pcntl_fork();
		if ($pid === false || $pid === -1) {
			die();
		} else if (!$pid) {
			// 启动计划任务 Worker
			$worker = new ResqueScheduler_Worker();
			$worker->logLevel = ResqueScheduler_Worker::LOG_NORMAL;
			fwrite(STDOUT, "*** Starting scheduler worker\n");
			$worker->work(1);
		}
	}

    public function dispatch(Job $job)
    {
        $job->dispatch($this);
    }
}
