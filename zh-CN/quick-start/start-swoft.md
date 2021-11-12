# 服务启动与管理

Swoft 拥有完善的命令行工具，和相应的服务器管理命令

## 帮助命令

```text
[root@swoft]# php bin/swoft -h
Console application description (Version: 2.0.0)

Usage:
  bin/swoft COMMAND [arg0 arg1 arg2 ...] [--opt -v -h ...]

Options:
      --debug      Setting the application runtime debug level(0 - 4)
      --no-color   Disable color/ANSI for message output
  -h, --help       Display this help message
  -V, --version    Show application version information
      --expand     Expand sub-commands for all command groups

Available Commands:
  http     Provide some commands to manage the swoft HTTP Server(alias: httpserver,httpServer,http-server)
  rpc      Class ServiceServerCommand
  test     Class TestCommand
  ws       Provide some commands to operate swoft WebSocket Server(alias: ws-server,wsserver,websocket)

More command information, please use: bin/swoft COMMAND -h
```

> `reload`只是重新释放一下内存，只在开发过程中有用，线上请勿使用。

> 更新代码时可以放心使用`restart`，`swoole`底层做了优化，不会中断正在处理的请求

## HTTP 服务器

```bash
// 启动服务
php bin/swoft http:start

// 守护进程启动
php bin/swoft http:start -d

// 重启
php bin/swoft http:restart

// 重新加载
php bin/swoft http:reload

// 关闭服务
php bin/swoft http:stop
```

## WebSocket服务器

启动WebSocket服务器,可选是否 **同时支持http处理**

```bash
// 启动服务
php bin/swoft ws:start

// 守护进程启动
php bin/swoft ws:start -d

// 重启
php bin/swoft ws:restart

// 重新加载
php bin/swoft ws:reload

// 关闭服务
php bin/swoft ws:stop
```

## RPC 服务器

使用独立的RPC服务器

```bash
// 启动服务
php bin/swoft rpc:start

// 守护进程启动
php bin/swoft rpc:start -d

// 重启
php bin/swoft rpc:restart

// 重新加载
php bin/swoft rpc:reload

// 关闭服务
php bin/swoft rpc:stop
```

## 启动信息

默认情况下，启动时会打印一些启动信息到控制台。

> 如果你的 `.env` 开启了 `SWOFT_DEBUG=1` 将会看到更多详细的启动与加载信息。

如果你想关闭这些信息，可以在 `app/Application` 添加：

```php

    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();
        // disable print console start log
        $config['enable'] = false;

        return $config;
    }
```

重启swoft，可以看到不会有任何信息输出了

