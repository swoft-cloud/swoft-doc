# swoft-crontab

## 简介

传统的crontab是外部调命令执行一个独立进程，swoft提供的crontab相当于一个代码任务定时器，定时规则和linux系统下的crontab规则一致，兼容所有语法。区别在于linux系统下的crontab的最小单位为**分**级，swoft的crontab的规则支持到最小单位为**秒**级。

## swoft-crontab规则

```php
// Swoft-Crontab 规则

0     1    2    3    4    5
*     *    *    *    *    *
-     -    -    -    -    -
|     |    |    |    |    |
|     |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
|     |    |    |    +----- month (1 - 12)
|     |    |    +------- day of month (1 - 31)
|     |    +--------- hour (0 - 23)
|     +----------- min (0 - 59)
+------------- sec (0-59)
```
