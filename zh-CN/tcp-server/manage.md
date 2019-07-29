# 启动与管理

在项目目录下执行如下命令可以看到tcp server的管理命令. 跟http server的管理命令一致.

```bash
$ php bin/swoft tcp
Description:
  There some commands for manage the tcp server

Usage:
  tcp:{command} [arguments] [options]

Commands:
  start    Start the tcp server
  stop     Stop the running server
  restart  Restart the running server

Options:
  -h, --help  Show help of the command group or specified command action
```

## 启动

- 前台运行

```bash
$ php bin/swoft tcp:start
```

> Notice: tcp server default port is `18309`

- 后台运行

```bash
$ php bin/swoft tcp:start -d
```

- 重新启动

```bash
$ php bin/swoft tcp:restart
```
