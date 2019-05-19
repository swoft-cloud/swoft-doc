# websocket 配置

websocket 的 host, port 等配置是都是完全可以自定义的。

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

### 启用http请求处理

默认的是没有启用http server功能的。如果你想开启ws时，同时处理http请求。

可以编辑： `app/bean.php`

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

