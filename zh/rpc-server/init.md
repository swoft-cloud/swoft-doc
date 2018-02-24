# 配置与启动
RPC 服务不仅仅可以单独启动，也可以HTTP Server 启动的时候，监听端口，启动RPC服务。无论哪种方式启动，都需要配置RPC服务基础信息。

## 服务配置
.env文件中，配置RPC服务的基础信息。

```
TCPABLE=true

TCP_HOST=0.0.0.0
TCP_PORT=8099
TCP_MODEL=SWOOLE_PROCESS
TCP_TYPE=SWOOLE_SOCK_TCP
TCP_PACKAGE_MAX_LENGTH=2048
TCP_OPEN_EOF_CHECK=false
```

- TCPABLE 参数，用于配置启动HTTP SERVER的同时是否启动RPC服务，单独RPC服务启动，不需配置该参数。
- TCP_HOST 服务器地址
- TCP_PORT 服务端口
- TCP_MODEL TCP模式
- TCP_TYPE TCP类型
- TCP_PACKAGE_MAX_LENGTH 参考 [https://wiki.swoole.com/wiki/page/301.html](https://wiki.swoole.com/wiki/page/301.html)
- TCP_OPEN_EOF_CHECK 参考 [https://wiki.swoole.com/wiki/page/285.html](https://wiki.swoole.com/wiki/page/285.html)


## 中间件配置
app/config/beans/base.php 配置RPC服务在执行过程中的中间件。

```php
return [
    // ......
    'ServiceDispatcher' => [
        'class' => ServiceDispatcher::class,
        'middlewares' => [
            'xxx:class',
        ]
    ]
    // ......
];
```




