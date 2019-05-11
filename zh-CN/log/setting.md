# 配置

无论是控制台日志还是应用日志都提供了很多灵活的参数，方便开发者。

## 控制台日志

启动应用里面 (`app\Application.php`) 重写父类方法，配置控制台日志参数。

```php
namespace App;


use Swoft\SwoftApplication;

/**
 * Class Application
 *
 * @since 2.0
 */
class Application extends SwoftApplication
{
    public function getCLoggerConfig(): array
    {
        return [
            'name'    => 'swoft',
            'enable'  => true,
            'output'  => true,
            'levels'  => [],
            'logFile' => ''
        ];
    }
}
```

- name 名称
- enable 是否开启
- output 是否打印的控制台
- levels 输入日志的级别，为空全部输出，具体日志级别配置值，可以引用 `Logger::NOTICE/...`
- logFile 控制台日志默认打印到控制台，也可以配置路径，同时写到指定文件


## 应用日志

在bean.php 里面配置应用日志的参数：

```php
return [
    'lineFormatter'      => [
        'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%] %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'noticeHandler'      => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/notice.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => [
            Logger::NOTICE,
            Logger::INFO,
            Logger::DEBUG,
            Logger::TRACE,
        ],
    ],
    'applicationHandler' => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => [
            Logger::ERROR,
            Logger::WARNING,
        ],
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


### 日志格式

`lineFormatter` 配置日志格式：

- format 日志输到文件格式
- dateFormat 日志输出时间格式

### 处理器

`noticeHandler` 和 `applicationHandler` 处理器，应用日志可以配置多个处理器，处理器可以把日志输出到文件、邮箱、第三方系统。

配置详细参数：

- class 配置采用哪种类型的，框架默认提供文件，用户可以自己扩展其它类型
- logFile 输出日志文件路径，支持别名
- formatter 日志输出使用日志格式，就是之前配置的日志格式
- levels 支持日志输出的日志级别