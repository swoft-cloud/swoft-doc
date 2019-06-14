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

ok, 现在 `IP:PORT` 上可以同时处理 http 和 ws 请求了。

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

