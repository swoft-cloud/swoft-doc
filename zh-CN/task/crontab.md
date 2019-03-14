# 定时任务

传统的 Crontab 是外部调命令执行一个独立进程，Swoft 提供的 Crontab 相当于一个代码任务定时器，定时规则和 Linux 系统下的 Crontab 规则一致，兼容所有语法。区别在于 Linux 系统下的Crontab 的最小单位为分级，Swoft 的 Crontab 的规则支持到最小单位为秒级。

# 注解

**@Scheduled**

- cron 定义定时任务执行时间格式，类似linux crontab，单位精确到秒

### cron格式

```

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


## 实例

```php
/**
 * Demo task
 *
 * @Task("demo")
 */
class DemoTask
{
    /**
     * crontab定时任务
     * 每一秒执行一次
     *
     * @Scheduled(cron="* * * * * *")
     */
    public function cronTask()
    {
        echo time() . "每一秒执行一次  \n";
        return 'cron';
    }

    /**
     * 每分钟第3-5秒执行
     *
     * @Scheduled(cron="3-5 * * * * *")
     */
    public function cronooTask()
    {
        echo time() . "第3-5秒执行\n";
        return 'cron';
    }
}
```
> 注意事项 定时任务所在的Task类注解coroutine不能为true

> 定时任务和普通任务是一样的，唯一不同的是，定时任务，到达时间点自动执行，无需手动投递任务。
