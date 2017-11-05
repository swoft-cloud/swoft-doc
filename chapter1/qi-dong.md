# 启动

启动服务支持HTTP和TCP单独启动，但是HTTP启动，可以配置是否启动TCP，`.env`中配置。

### 常用命令

**帮助命令**

```py
[root@0dd3950e175b bin]# php swoft -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php swoft -h

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
php swoft start

// 守护进程启动
php swoft start -d

// 停止
php swoft stop

// 重载
php swoft reload

// 重启
php swoft restart
```

**RPC启动**

```
// 启动
php swoft rpc:start

// 守护进程启动
php swoft rpc:start -d

// 停止
php swoft rpc:stop

// 重载
php swoft rpc:reload

// 重启
php swoft rpc:restart
```

**.env**

```dotenv
# Server
PFILE=/tmp/swoft.pid
PNAME=php-swoft
TCPABLE=true
CRONABLE=false
AUTO_RELOAD=true

# HTTP
HTTP_HOST=0.0.0.0
HTTP_PORT=80

# TCP
TCP_HOST=0.0.0.0
TCP_PORT=8099
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



