# RPC server 服务命令

在项目根目录执行如下命令

```shell
$ php bin/swoft rpc
Usage:
  bin/swoft rpc:COMMAND [--opt ...] [arg ...]

Global Options:
      --debug      Setting the application runtime debug level(0 - 4)
      --no-color   Disable color/ANSI for message output
  -h, --help       Display this help message
  -V, --version    Show application version information

Commands:
  reload      Reload worker processes
  restart     Restart the http server
  start       Start the http server
  stop        Stop the currently running server

Example:
 bin/swoft rpc:start     Start the rpc server
 bin/swoft rpc:stop      Stop the rpc server


```

Rpc 的命令都在 `Commands`

- reload 重新加载 `worker` 进程
- restart 重启 RPC 服务器
- start 启动 RPC 服务器
- stop 停止 RPC 服务器

## 使用

- 前台运行

```shell
$ php bin/swoft rpc:start
                            Information Panel
  **********************************************************************
  * RPC      | Listen: 0.0.0.0:18307, type: TCP, mode: Process, worker: 1
  **********************************************************************

RPC server start success !
```

- 后台运行

```shell
$ php bin/swoft rpc:start -d
                            Information Panel
  **********************************************************************
  * RPC      | Listen: 0.0.0.0:18307, type: TCP, mode: Process, worker: 1
  **********************************************************************

RPC server start success !

```