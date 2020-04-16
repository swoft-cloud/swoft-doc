# websocket 配置

websocket 的 host, port 等配置是都是完全可以自定义的。
配置需要编辑 `app/bean.php` 文件，下面列举了一些简单的配置，你也可以自由组合同时提供多种服务。

> websocket server 的默认端口是 `18308`

## 可配置项

可配置项用于 ws server bean 配置，除了 `class` 其他都是 ws server 的属性。

- `class` 指定 websocket server 的处理类
- `port` 指定 websocket server 的端口
- `listener` 指定其他一同启动的服务，添加端口服务监听，可以多个。
    - rpc 启动 `RPC` 服务
- `on` 配置监听的事件
    - 注册事件、设置对应事件的处理监听，事件触发组件调用，在任务里面使用
- `setting` 这里是参考 [Swoole Server配置选项](https://wiki.swoole.com/wiki/page/274.html)
- `pidFile` 设置进程 `pid文件` 位置，默认值 `@runtime/swoft.pid`
- `mode` 运行的模式，参考 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第三个参数
- `type` 指定Socket的类型，支持TCP、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 等 [Swoole Server 构造函数](https://wiki.swoole.com/wiki/page/14.html) 第四个参数

## 基础配置

```php
    // ...
    'wsServer'   => [
        'class'   => WebSocketServer::class,
        'port' => 18307,
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
```

## 启用http请求处理

默认的是没有启用http server功能的。如果你想开启ws时，同时处理http请求。

```php
    // ...
    'wsServer'   => [
        'class'   => WebSocketServer::class,
        'on'      => [
            // 加上如下一行，开启处理http请求
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
```

ok, 现在 `IP:PORT` 上可以同时处理 http 和 ws 请求了。你可以到此页面 http://swoft.io/wstest 进行简单测试

## 启用wss支持

跟在http server启用https类似，在swoft里启用 wss 也非常简单，添加如下的配置即可：

```php
'httpServer' => [
    'type' => SWOOLE_SOCK_TCP | SWOOLE_SSL,
    
    /* @see WebSocketServer::$setting */
    'setting'  => [
        'ssl_cert_file' => '/my/certs/2288803_www.domain.com.pem',
        'ssl_key_file'  => '/my/certs/2288803_www.domain.com.key',
    ]
]
```

> 注意： 你必须安装 OpenSSL 库，并且确保安装swoole时是启用了 ssl 选项的。同时，需要设置 `'type' => SWOOLE_SOCK_TCP | SWOOLE_SSL`

## 添加RPC服务
 
如果你想开启ws时，同时启动RPC Server服务。

```php
    // ...
    'wsServer'   => [
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

- websocket server
- http server
- task process
- rpc server

```php
    // ...
    'wsServer'   => [
        'class'   => WebSocketServer::class,
        'port' => 18307,
        'on'      => [
            // 开启处理http请求支持
            SwooleEvent::REQUEST => bean(RequestListener::class),
            // 启用任务必须添加 task, finish 事件处理
            SwooleEvent::TASK   => bean(TaskListener::class),  
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        'listener' => [
            // 引入 rpcServer
            'rpc' => \bean('rpcServer')
        ],
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
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

ok, 现在通过 `php bin/swoft ws:start` 启动的服务器，就支持上面的全部功能了

## 连接数据存储

websocket server 是通过 `wsConnectionManager` bean 管理所有的websocket连接的。

每个连接握手后的连接请求信息等都会存储到当前worker的 `wsConnectionManager` bean（并且会同步到 `Swoft\Session\Session` 类中），这样在后面进行消息路由时就能找到对应消息模块，控制器。

`wsConnectionManager` 默认配置的存储驱动是 `Swoft\Session\ArrayStorage`。

> 如果worker重启或者异常退出都会导致 `ArrayStorage` 里面的数据被清空，从而出现 `session information has been lost of the SID: 23` 这样的错误信息。

### 自定义连接信息存储

为了保持连接信息数据不受worker 退出/重启的影响，你可以覆盖设置 `wsConnectionManager` 的 `storage` 存储驱动。

配置参考如下(`app/bean.php`)：

```php
'wsConnectionManager' => [
    'storage' => bean('wsConnectionStorage')
],
'wsConnectionStorage' => [
    'class' => \Swoft\Session\SwooleStorage::class,
],
```

这里使用了swoft内部提供的基于swoole table 封装的存储驱动 `\Swoft\Session\SwooleStorage`，它是跨进程的并且生命周期跟随server，不会受到worker重启的影响。

当worker重启后，再收到客户端的消息请求时，如果没有从内存中拿到连接信息，会自动从存储的连接信息中恢复连接对象。

> 当然，你也可以自定义自己的存储驱动，可以使用redis等。只需要实现 `Swoft\Contract\SessionStorageInterface` 接口，然后参照上面的配置覆盖默认设置即可。

