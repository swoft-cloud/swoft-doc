# 进程
Swoft 框架里面的进程分为Server 前置进程和自定义进程两种。

- Server 前置进程，Http Server 启动之前创建的守护进程([swoole_server->addProcess](https://wiki.swoole.com/wiki/page/390.html))
- 自定义进程，用户在业务逻辑中创建自定义的进程