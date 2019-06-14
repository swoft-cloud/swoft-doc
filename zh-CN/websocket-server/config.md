# websocket 配置

websocket 的 host, port 等配置是都是完全可以自定义的。配置需要编辑 `app/bean.php` 文件

> websocket server 的默认端口是 `18308`

## ws server 配置

可以编辑： `app/bean.php`

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

