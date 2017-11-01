# 启动

启动服务支持HTTP和TCP单独启动，但是HTTP启动，可以配置是否启动TCP，swoft.ini中配置。

### 常用命令

**帮助命令**

```py
[root@0dd3950e175b bin]# php swoft.php -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php swoft.php -h

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
php swoft.php start

// 守护进程启动
php swoft.php start -d

// 停止
php swoft.php stop

// 重载
php swoft.php reload

// 重启
php swoft.php restart
```

**RPC启动**

```py
// 启动
php swoft.php rpc:start

// 守护进程启动
php swoft.php rpc:start -d

// 停止
php swoft.php rpc:stop

// 重载
php swoft.php rpc:reload

// 重启
php swoft.php rpc:restart
```

**Swoft.ini**

```py
[server]
pfile = '/tmp/swoft.pid';
pname = "php-swoft";
tcpable = 1;

[tcp]
host = "0.0.0.0"
port = 8099
model = SWOOLE_PROCESS
type = SWOOLE_SOCK_TCP
package_max_length = 2048
open_eof_check = 0

[http]
host = "0.0.0.0"
port = 80
model = SWOOLE_PROCESS
type = SWOOLE_SOCK_TCP

[process]
reload = 'Swoft\Process\ReloadProcess'
;myProcess = 'App\Process\MyProcess'

[setting]
worker_num = 3
max_request = 10000
daemonize = 0;
dispatch_mode = 2
log_file = "@runtime/swoole.log"
task_worker_num = 1
```



