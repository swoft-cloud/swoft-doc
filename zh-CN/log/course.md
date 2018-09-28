# 配置与使用

## 配置
app/config/beans/log.php

```php
return [
    'noticeHandler'      => [
        'class'     => \Swoft\Log\FileHandler::class,
        'logFile'   => '@runtime/logs/notice.log',
        'formatter' => '${lineFormatter}',
        'levels'    => [
            \Swoft\Log\Logger::NOTICE,
            \Swoft\Log\Logger::INFO,
            \Swoft\Log\Logger::DEBUG,
            \Swoft\Log\Logger::TRACE,
        ],
    ],
    'applicationHandler' => [
        'class'     => \Swoft\Log\FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => '${lineFormatter}',
        'levels'    => [
            \Swoft\Log\Logger::ERROR,
            \Swoft\Log\Logger::WARNING,
        ],
    ],
    'logger'             => [
        'name'          => APP_NAME,
        'enable'        => true,
        'flushInterval' => 100,
        'flushRequest'  => true,
        'handlers'      => [
            '${noticeHandler}',
            '${applicationHandler}',
        ],
    ],
];

```

- enable 是否开启日志，默认true，如果为false 不会有任何日志打印
- flushInterval 指定日志累计到了多少条，刷新到磁盘一次
- flushRequest 是否每个请求刷新一次，性能有损耗
- handlers 定义日志输出方式，系统默认配置的文件，用户可扩展其它输出方式，这里配置即可
- handler 每一个日志处理器，都可以配置，处理日志的格式集合，可以把日志输出到多个地方。
- formatter 日志格式化类，默认使用 lineFormatter(即 Monolog\Formatter\LineFormatter)，此处可自定义格式化类，需继承   
  Mogolog\Formatter\NormalizerFormatter，并实现 LineFormatter 中对应的公有方法，修改常量SIMPLE_FORMAT和format方法
  即可对输出的日志格式做自定义。值得注意的是，该类需要放入Bean容器

## 实例

```php
// 标记开始
App::profileStart("tag");

// 直接输出异常
App::error(new \Exception("error exception"));
App::error("this errro log");
App::info("this errro log");

// 数组出
App::error(['name' => 'boy']);
App::debug("this errro log");

// 标记结束
App::profileEnd("tag");

// 统计缓存命中率
App::counting("cache", 1, 10);

```

- profileStart/profileEnd 用于统计两个标记点时间
- counting 统计缓存命中率


## 日志格式

```
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:123,app\controllers\IndexController->actionLog] Exception: error exception in /home/worker/data/www/swoft/app/controllers/IndexController.php:123 Stack trace: #0 /home/worker/data/www/swoft/src/base/Controller.php(77): app\controllers\IndexController->actionLog() #1 /home/worker/data/www/swoft/src/base/Controller.php(58): swoft\base\Controller->runActionWithParams('log', Array) #2 /home/worker/data/www/swoft/src/base/Controller.php(41): swoft\base\Controller->runAction('log', Array) #3 /home/worker/data/www/swoft/src/web/Application.php(248): swoft\base\Controller->run('log', Array) #4 /home/worker/data/www/swoft/src/web/Application.php(140): swoft\web\Application->runControllerWithFilters(Object(swoft\web\Request), Object(swoft\web\Response), Object(app\controllers\IndexController), 'log', Array) #5 {main}
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:124,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:126,app\controllers\IndexController->actionLog] {"name":"boy"}
2017/08/06 03:11:00 [info] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:125,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [debug] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:127,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [notice] [swoft] [logid:598688c45cbbf] [spanid:0] [168(ms)] [2(MB)] [/index/log] [] profile[app.route.match=7.52(ms)/1,app.route=7.53(ms)/1,tag=13.58(ms)/1] counting[cache=1/10]
```

- 切记每个请求只会有一条notice日志，框架底层自动生成的，用户不用关心。
