# 启动

启动服务支持HTTP和TCP单独启动，但是HTTP启动，可以配置是否启动TCP，`.env`中配置。

## 常用命令

**帮助命令**

在项目目录下：

```
$ php bin/swoft -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php bin/swoft -h

Commands:
  entity  the group command list of database entity
  rpc     the group command list of rpc server
  server  the group command list of http-server

Options:
  -v,--version  show version
  -h,--help     show help
```

**HTTP启动**

```
// 启动
php bin/swoft start

// 守护进程启动
php bin/swoft start -d

// 停止
php bin/swoft stop

// 重载
php bin/swoft reload

// 重启
php bin/swoft restart
```

**RPC启动**

```
// 启动
php bin/swoft rpc:start

// 守护进程启动
php bin/swoft rpc:start -d

// 停止
php bin/swoft rpc:stop

// 重载
php bin/swoft rpc:reload

// 重启
php bin/swoft rpc:restart
```

**.env**

```ini
# Server
PFILE=/tmp/swoft.pid
PNAME=php-swoft
TCPABLE=true
CRONABLE=false
AUTO_RELOAD=true

# HTTP
HTTP_HOST=0.0.0.0
HTTP_PORT=80
HTTP_MODEL=SWOOLE_PROCESS
HTTP_TYPE=SWOOLE_SOCK_TCP

# TCP
TCP_HOST=0.0.0.0
TCP_PORT=8099
TCP_MODEL=SWOOLE_PROCESS
TCP_TYPE=SWOOLE_SOCK_TCP
TCP_PACKAGE_MAX_LENGTH=2048
TCP_OPEN_EOF_CHECK=false

# Crontab
CRONTAB_TASK_COUNT=1024
CRONTAB_TASK_QUEUE=2048

# Settings
WORKER_NUM=1
MAX_REQUEST=10000
DAEMONIZE=0
DISPATCH_MODE=2
LOG_FILE=@runtime/swoole.log
TASK_WORKER_NUM=1

```



