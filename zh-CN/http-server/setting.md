# Http Server 配置参数

## 可配置项

- class 指定 `Http Server` 的处理类
- port 指定 Http `Server` 的端口
- listener 指定其他一同启动的服务
    - rpc 启动 `RPC` 服务
- on 配置监听的事件
    - 注册事件、设置对应事件的处理监听，事件触发组件调用，在任务里面使用
- setting 这里是参考 [Swoole Server配置选项](https://wiki.swoole.com/wiki/page/274.html)
- pidFile 设置进程 `pid文件` 位置，默认值 `@runtime/swoft.pid`
- mode 运行的模式，参考 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第三个参数
- type 指定Socket的类型，支持TCP、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 6种 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第四个参数

## 基础配置

Swoft应用的 Http Server 配置在 `app/bean.php` 中。在这个文件里，你可以看到 Http Server数组里面包含了 Http Server 的基本信息。

```php
'httpServer' => [
    'class'    => HttpServer::class,
    'port'     => 18306,
    'listener' => [
        'rpc' => \bean('rpcServer')
    ],
    'on'       => [
        SwooleEvent::TASK   => \bean(TaskListener::class),  // Enable task must task and finish event
        SwooleEvent::FINISH => \bean(FinishListener::class)
    ],
    /* @see HttpServer::$setting */
    'setting'  => [
        'task_worker_num'       => 12,
        'task_enable_coroutine' => true
    ]
],
```

## 启用Task处理

启用Task进程处理任务

```php
'httpServer' => [
    'class'    => HttpServer::class,
    'port'     => 18306,
    'on'       => [
        // Enable task must task and finish event
        SwooleEvent::TASK   => \bean(TaskListener::class),  
        SwooleEvent::FINISH => \bean(FinishListener::class)
    ],
    /* @see HttpServer::$setting */
    'setting'  => [
        'task_worker_num'       => 12,
        'task_enable_coroutine' => true
    ]
],
```

## 启用https支持

在swoft里启用 https 支持也非常简单，添加如下的配置即可：

```php
'httpServer' => [
    'type' => SWOOLE_SOCK_TCP | SWOOLE_SSL,
    
    /* @see HttpServer::$setting */
    'setting'  => [
        'ssl_cert_file' => '/my/certs/2288803_www.domain.com.pem',
        'ssl_key_file'  => '/my/certs/2288803_www.domain.com.key',
    ]
]
```

> 注意： 你必须安装 OpenSSL 库，并且确保安装swoole时是启用了 ssl 选项的。
