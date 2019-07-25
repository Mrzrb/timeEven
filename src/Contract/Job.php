<?php

namespace RobinSchedule\Contract;

use RobinSchedule\TimeEven;

/**
 * Class Job
 * @author Me
 */
interface Job
{
    const ASYNC = 'async';
    const SYNC = 'sync';

    public function dispatch(TimeEven $instance);

    public function setUp();

    public function perform();

    public function tearDown();
}
