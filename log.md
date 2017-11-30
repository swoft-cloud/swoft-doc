# 日志

日志记录一般用户问题的问的分析，系统的定位。目前日志规划有debug trace error info warning notice等级别。每种不同的级别用户记录不同重要程度的信息。系统会为每一个请求生成一条notice,并且一个请求产生的所有日志都有一个相同的logid,notice里面记录该请求的详细信息，比如uri 总共耗时 缓存或db操作时间等等信息。  
使用很简单，如下：

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

输出日志格式如下:

```python
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:123,app\controllers\IndexController->actionLog] Exception: error exception in /home/worker/data/www/swoft/app/controllers/IndexController.php:123 Stack trace: #0 /home/worker/data/www/swoft/src/base/Controller.php(77): app\controllers\IndexController->actionLog() #1 /home/worker/data/www/swoft/src/base/Controller.php(58): swoft\base\Controller->runActionWithParams('log', Array) #2 /home/worker/data/www/swoft/src/base/Controller.php(41): swoft\base\Controller->runAction('log', Array) #3 /home/worker/data/www/swoft/src/web/Application.php(248): swoft\base\Controller->run('log', Array) #4 /home/worker/data/www/swoft/src/web/Application.php(140): swoft\web\Application->runControllerWithFilters(Object(swoft\web\Request), Object(swoft\web\Response), Object(app\controllers\IndexController), 'log', Array) #5 {main}
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:124,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [error] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:126,app\controllers\IndexController->actionLog] {"name":"boy"}
2017/08/06 03:11:00 [info] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:125,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [debug] [swoft] [logid:598688c45cbbf] [spanid:0] trace[IndexController.php:127,app\controllers\IndexController->actionLog] this errro log
2017/08/06 03:11:00 [notice] [swoft] [logid:598688c45cbbf] [spanid:0] [168(ms)] [2(MB)] [/index/log] [] profile[app.route.match=7.52(ms)/1,app.route=7.53(ms)/1,tag=13.58(ms)/1] counting[cache=1/10]
```

# 日志配置

注意的是日志必须配置一个输出handler,如下定义两个handler,实现把不同的日志级别输出到不同的日志文件里面。

```php
return [

    // ...

    "noticeHandler"      => [
        "class"     => \swoft\log\FileHandler::class,
        "logFile"   => RUNTIME_PATH . "/notice.log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \swoft\log\Logger::NOTICE,
            \swoft\log\Logger::INFO,
            \swoft\log\Logger::DEBUG,
            \swoft\log\Logger::TRACE,
        ]
    ],
    "applicationHandler" => [
        "class"     => \swoft\log\FileHandler::class,
        "logFile"   => RUNTIME_PATH . "/error.log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \swoft\log\Logger::ERROR,
            \swoft\log\Logger::WARNING
        ]
    ],
    "logger" => [
        "class"         => \swoft\log\Logger::class,
        "name"          => SYSTEM_NAME,
        "flushInterval" => 1,    // 定义输出日志的条数，日志累计达到多少条输出一次到文件，默认是10
        "handlers"      => [
            '${noticeHandler}',
            '${applicationHandler}'
        ]
    ]
    
    // ...

];
```



