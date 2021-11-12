# 应用日志

## 配置

在bean.php 里面配置应用日志的参数：

```php
return [
    'lineFormatter'      => [
        'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%] %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'noticeHandler'      => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/notice-%d{Y-m-d}.log',  // 2.0.6 支持日志按时间切割
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'notice,info,debug,trace',
    ],
    'applicationHandler' => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'error,warning',
    ],
    'logger'             => [
        'flushRequest' => false,
        'enable'       => false,
        'handlers'     => [
            'application' => \bean('applicationHandler'),
            'notice'      => \bean('noticeHandler'),
        ],
    ]
];
```

此配置也是框架默认的配置文件，把应用日志按日志级别分别写到两个不同的文件里面。

> swoft 2.0.3 `levels` 修改成字符串，方便开发者覆盖框架默认配置

### 日志格式

`lineFormatter` 配置日志格式：

- format 日志输到文件格式
- dateFormat 日志输出时间格式

### 日志处理器

`noticeHandler` 和 `applicationHandler` 处理器，应用日志可以配置多个处理器，处理器可以把日志输出到文件、邮箱、第三方系统。

配置详细参数：

- class 配置采用哪种类型的，框架默认提供文件，用户可以自己扩展其它类型
- logFile 输出日志文件路径，支持别名
    - `2.0.6` 支持日志按时间切割，格式： `%d{Y-m-d}` ， `{ }` 中的格式适用 PHP date format
- formatter 日志输出使用日志格式，就是之前配置的日志格式
- levels 支持日志输出的日志级别

### 日志配置

`logger` 日志配置：

- name 名称
- flushInterval 日志输出频率默认 1
- flushRequest 是否每个请求结束输出日志开，默认 `false`
- enable 是否开启日志，默认 `false`
- json 是否 JSON 格式输出，默认 `false`
- items 日志其它数据，配置是一个数组集合

## 使用

开发者直接通过 `Log` 类静态方法，打印日志。应用日志根据不同的需求分为如下不同的日志记录：

- emergency
- debug
- alert
- info
- warning
- error

```php

// Tag start
Log::profileStart('tagName');
        
Log::debug('this %s log', 'debug');
Log::info('this %s log', 'info');
Log::warning('this %s log', 'warning');
Log::error('this %s log', 'error');
Log::alert('this %s log', 'alert');
Log::emergency('this %s log', 'emergency');

// Pushlog
Log::pushLog('key', 'value');
Log::pushLog('key', ['value']);
Log::pushLog('key', 'value');

// Tag end
Log::profileEnd('tagName');

// Counting
Log::counting('mget', 1, 10);
Log::counting('mget', 2, 10);

// 2019-05-11 06:57:27 [info] [swoft] [request] [tid:4] [cid:4] [traceid:] [spanid:] [parentid:] trace[LogController.php:29,App\Http\Controller\LogController->test] info message
// 2019-05-11 06:57:27 [debug] [swoft] [request] [tid:4] [cid:4] [traceid:] [spanid:] [parentid:] trace[LogController.php:35,App\Http\Controller\LogController->test] this debug log
// 2019-05-11 06:57:27 [info] [swoft] [request] [tid:4] [cid:4] [traceid:] [spanid:] [parentid:] trace[LogController.php:36,App\Http\Controller\LogController->test] this info log
// 2019-05-11 06:57:27 [warning] [swoft] [request] [tid:4] [cid:4] [traceid:] [spanid:] [parentid:] trace[LogController.php:37,App\Http\Controller\LogController->test] this warning log
// 2019-05-11 06:57:27 [error] [swoft] [request] [tid:4] [cid:4] [traceid:] [spanid:] [parentid:] trace[LogController.php:38,App\Http\Controller\LogController->test] this error log
// 2019-05-11 06:57:27 [notice] [swoft] [request] [tid:4] [cid:5] [traceid:] [spanid:] [parentid:] [8.52(ms)] [27(MB)] [/log/test] [key=value key=["value"] key=value] profile[tagName=5.02(ms)/2] counting[mget=3/20]
```

> 日志级别可以根据自己的业务定义不同的含义，`debug` 日志级别只有开启 `APP_DEBUG` 默认才会输出. `notice` 日志级别，每个请求结束框架自动追加上的一条日志，该条日志会记录这次请求，框架和业务运行的详细信息，比如数据库操作记录、缓存操作记录以及请求信息。日志方法可以像 `sprintf()` 函数一样传递参数，底层自动封装。

除常用的日志级别，框架还提供 `pushLog` `profile`

- `pushLog` 用于记录请求执行过程中的参数，`notice` 日志里面可以查看
- `profile` 使用的时，需要定义一个标记，标记开始和结束为止，该方法可以记录标记之间运行时间，`notice` 日志里面可以查看。
