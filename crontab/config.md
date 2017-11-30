# 配置

Crontab定时任务配置很简单，通过`.env`即可选择开启或者不开启。

```dotenv
# Server
PFILE=/tmp/swoft.pid
PNAME=php-swoft
TCPABLE=true
CRONABLE=false
```
- CRONABLE = false ；关闭

- CRONABLE = true ；开启

在 Server 的 section 里面，可以看到一个选项叫 `CRONABLE`，这个是开启 Crontab 任务的开关。默认值为:**false**

```dotenv
# Crontab
CRONTAB_TASK_COUNT=1024
CRONTAB_TASK_QUEUE=2048
```
在 Crontab 的 section 里面，可以看到有两个选项可以选择.

- CRONTAB_TASK_COUNT : 任务数量

- CRONTAB_TASK_QUEUE : 队列一次处理的最大数量

CRONTAB_TASK_COUNT 的计算通过 `App/Task/` 目录下的 Task 类里面的定时方法累计而得，默认为**1024**

CRONTAB_TASK_QUEUE 的计算则通过 Crontab 规则解析后的需要执行的的次数累计而得，默认为**2048**

> 提示：除非不够用了，否则推荐使用官方默认参数
