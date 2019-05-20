# 配置参数

RPC 服务启动有单独启动和集成其它服务(Http/Websocket)两种方式，无论那种方式都首先要在 bean.php 配置RPC。

```php
return [
    'rpcServer'  => [
        'class' => ServiceServer::class,
        'port' => 18308,
    ],
]
```

- port 配置启动端口号
- setting 启动配置参数，对应 `swooleServer->setting`

Http server 启动中集成 RPC 服务:

```php
return [
    'httpServer' => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        
        // ...
    ],
]
```

- listener 单独监听一个RPC服务，且同时可以监听多个 RPC 服务

> 如果是单独启动，无效其它配置直接可以启动。