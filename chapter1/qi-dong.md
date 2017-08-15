# 启动

启动服务支持HTTP和TCP同时启动，swoft.ini中配置。

**常用命令**

```java
//启动服务,是否是守护进程，根据swoft.ini配置
php swoft.php start

//守护进程启动，覆盖swoft.ini守护进程配置
php swoft.php start -d

// 重新加载
php swoft.php reload


// 关闭服务
php swoft.php stop
```

**Swoft.ini**

```py
[swoft]
;;;;;;;;;;;;;;;;;;;
; About swoft.ini  ;
;;;;;;;;;;;;;;;;;;;


[server]
pfile = '/tmp/swoft.pid';
pname = "php-swf";


[tcp]
enable = 1;
host = "0.0.0.0"
port = 8099
type = SWOOLE_SOCK_TCP


[http]
host = "0.0.0.0"
port = 80
model = SWOOLE_PROCESS
type = SWOOLE_SOCK_TCP


[setting]
worker_num = 4
max_request = 10000
daemonize = 0;
dispatch_mode = 2
log_file = SWOOLE_LOG_PATH;
```



