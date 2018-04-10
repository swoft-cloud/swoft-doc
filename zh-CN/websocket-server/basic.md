# 启动与管理

在项目目录下执行如下命令可以看到websocket server的管理命令

```bash
$ php bin/swoft ws
Description:
  There some commands for manage the webSocket server

Usage:
  ws:{command} [arguments] [options]

Commands:
  start    Start the webSocket server
  reload   Reload worker processes for the running server
  stop     Stop the running server
  restart  Restart the running server

Options:
  -h, --help  Show help of the command group or specified command action

```

## 启动

- 前台运行

```bash
$ php bin/swoft ws:start
                                 Server Information
************************************************************************************
* WS   | host: 0.0.0.0, port: 9088, type: 1, worker: 1, mode: 3 (http is Enabled)
* TCP  | host: 0.0.0.0, port: 9099, type: 1, worker: 1 (Disabled)
************************************************************************************
Server has been started. (master PID: 86408, manager PID: 86409)
You can use CTRL + C to stop run.
```

- 后台运行

```bash
$ php bin/swoft ws:start -d
```

> `http is Enabled` 标明同时启用了http请求处理功能

## 使用

如果你注册了ws的路由处理控制器，现在就可以通过浏览器等ws客户端连接上server了
