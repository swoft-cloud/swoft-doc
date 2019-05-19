# 启动与管理

在项目目录下执行如下命令可以看到websocket server的管理命令. 跟http server的管理命令一致.

```bash
$ php bin/swoft ws
Description:
  There some commands for manage the webSocket server

Usage:
  ws:{command} [arguments] [options]

Commands:
  start    Start the webSocket server
  stop     Stop the running server
  restart  Restart the running server

Options:
  -h, --help  Show help of the command group or specified command action
```

## 启动

- 前台运行

```bash
$ php bin/swoft ws:start
```

- 后台运行

```bash
$ php bin/swoft ws:start -d
```

## 使用

如果你注册了ws的路由处理模块，现在就可以通过浏览器等ws客户端连接上server了



