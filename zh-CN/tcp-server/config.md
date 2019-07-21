# tcp server 配置

tcp server 的 host, port 等配置是都是完全可以自定义的。
配置需要编辑 `app/bean.php` 文件，下面列举了一些简单的配置，你也可以自由组合同时提供多种服务。

> tcp server 的默认端口是 `18309`

## 可配置项

可配置项用于 `tcpServer` bean 配置，除了 `class` 其他都是 `TcpServer` 的属性。

- `class` 指定 tcp server 的处理类
- `port` 指定 tcp server 的端口
- `listener` 指定其他一同启动的服务，添加端口服务监听，可以多个。
    - rpc 启动 `RPC` 服务
- `on` 配置监听的事件
    - 注册swoole事件、设置对应事件的处理监听
- `setting` 这里是参考 [Swoole Server配置选项](https://wiki.swoole.com/wiki/page/274.html)
- `pidFile` 设置进程 `pid文件` 位置，默认值 `@runtime/swoft.pid`
- `mode` 运行的模式，参考 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第三个参数
- `type` 指定Socket的类型，支持TCP、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 等 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第四个参数

## 基础配置

```php
    // ...
    'tcpServer'   => [
        'class'   => TcpServer::class,
        'port' => 18309,
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see TcpServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
```

## 添加RPC服务
 
如果你想运行tcp server时，同时启动RPC Server服务。

```php
    // ...
    'tcpServer'   => [
        'listener' => [
            'rpc' => \bean('rpcServer') // 引入 rpcServer
        ],
    ],
    'rpcServer'  => [
        'class' => ServiceServer::class,
        'port' => 18308,
    ],
```

## 启用全部功能

- task process
- rpc server

```php
    // ...
    'tcpServer'   => [
        'class'   => TcpServer::class,
        'port' => 18307,
        'on'      => [
            // 启用任务必须添加 task, finish 事件处理
            SwooleEvent::TASK   => bean(TaskListener::class),  
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        'listener' => [
            // 引入 rpcServer
            'rpc' => \bean('rpcServer')
        ],
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see TcpServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
            // 任务需要配置 task worker
            'task_worker_num'       => 2,
            'task_enable_coroutine' => true
        ],
    ],
    'rpcServer'  => [
        'class' => ServiceServer::class,
        'port' => 18308,
    ],
```

ok, 现在通过 `php bin/swoft tcp:start` 启动的服务器，就支持上面的全部功能了

