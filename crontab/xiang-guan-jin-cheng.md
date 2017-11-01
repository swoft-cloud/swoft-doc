# 相关进程

```php
[process]
reload = 'Swoft\Process\ReloadProcess'
cronTimer = 'Swoft\Process\CronTimerProcess'
cronExec = 'Swoft\Process\CronExecProcess'
```

`bin/swoft.ini` 配置文件里面有一项 `process`，该项可以允许用户设定自定义进程，其中有三项是官方定义的进程，其中和**crontab**相关的进程有**2**个。

- cronTimer : 检测进程

- cronExec : 执行进程

进程的名字命(cronTimer/cronExec)名**允许**修改，但是指定的类**不允许**修改。
