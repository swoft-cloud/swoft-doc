# 配置

crontab定时任务配置很简单，通过`bin/swoft.ini`即可选择开启或者不开启。

```php
[server]
pfile = '/tmp/swoft.pid';
pname = "php-swoft";
tcpable = 1;
cronable = 0;
```
- cronable = 0 ；关闭

- cronable = 1 ；开启

在server的section里面，可以看到一个选项叫`cronable`，这个是开启crontab任务的开关。默认值为:**0**

```php
[crontab]
task_count = 1024;
task_queue = 2048;
```
在crontab的section里面，可以看到有两个选项可以选择.

- task_count : 任务数量

- task_queue : 队列一次处理的最大数量

task_count的计算通过`App/Task/`目录下的Task类里面的定时方法累计而得，默认为**1024**

task_queue的计算则通过crontab规则解析后的需要执行的的次数累计而得，默认为**2048**

友情提示：除非不够用了，否则推荐使用官方默认参数
