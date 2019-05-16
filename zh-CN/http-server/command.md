# Http服务命令

在项目根目录执行如下命令

```shell
$ php bin/swoft http
Provide some commands to manage the swoft HTTP Server

Group: http (alias: httpserver,httpServer,http-server)
Usage:
  bin/swoft http:COMMAND [--opt ...] [arg ...]

Global Options:
      --debug      Setting the application runtime debug level(0 - 4)
      --no-color   Disable color/ANSI for message output
  -h, --help       Display this help message
  -V, --version    Show application version information

Commands:
  reload    Reload worker processes
  restart   Restart the http server
  start     Start the http server
  stop      Stop the currently running server

Example:
 bin/swoft http:start     Start the http server
 bin/swoft http:stop      Stop the http server

View the specified command, please use: bin/swoft http:COMMAND -h

```

Http的命令都在 `Commands`

- reload 重新加载 `worker` 进程
- restart 重启 Http 服务器
- start 启动 Http 服务器
- stop 停止 Http 服务器

## 使用

- 前台运行

```shell
$ php bin/swoft http:start
                            Information Panel
  ***********************************************************************
  * HTTP     | Listen: 0.0.0.0:18306, type: TCP, mode: Process, worker: 1
  * rpc      | Listen: 0.0.0.0:18307, type: TCP
  ***********************************************************************

HTTP server start success !
```

- 后台运行

```shell
$ php bin/swoft http:start -d
                            Information Panel
  ***********************************************************************
  * HTTP     | Listen: 0.0.0.0:18306, type: TCP, mode: Process, worker: 1
  * rpc      | Listen: 0.0.0.0:18307, type: TCP
  ***********************************************************************

HTTP server start success !
```

在浏览器通过 `http://127.0.0.1:18306/` 访问Http服务