# timeEven

timeeven是一个基于redis的队列事件包，可以支持队列事件，延时队列事件， 在php-resque (一个ruby的高人气的队列包resque改编而来)的基础上构建的，可以很方便的融入yaf中，为yaf提供延时队列事件功能。

# Features

## 事件队列
timeeven用一种很方便的方式提供了事件队列，可以很容易的融入框架。

## 延时事件队列
timeeven在事件队列的基础上，通过php-resque-schedule提供了延时队列的功能。

# Installation

```
composer require zhangrb/time-even
```

# Usage

## 队列配置

```php
$config = [
    'host' => 'localhost',
	'port' => '6379',
];
$app = new TimeEven(null, $config);

```

## 普通队列

可以通过集成TimeEvenJob,并且更改type为self::SYNC即可设置为普通队列


```php
$job = (new TimeEvenJob())->withPayload(['test' => 123]);

```

## 延时队列
可以通过集成TimeEvenJob,并且更改type为self::ASYNC即可设置为普通队列,并通过delay来设置需要延时的时间

## 分派Job

```php
$app->dispatch($job);
```

## 启动工作进程


```
$app->work('test-job', 3);
$app->schedule();
```
