# 定时任务

在某些情况下需要定时的去执行某些任务，通常我们会使用 Linux 系统自带的 Crontab 去定时的执行我们编写好的脚本，但是这样及其不方便，首先 Linux 系统默认的 Crontab 最小单位只能支持到分钟，无法支持秒级任务，其次，如果我们重新编写脚本，则不能很方便友好的复用框架内的资源，如 Mysql 连接资源，框架中的各种类库。针对以上问题，框架为我们内置了一个 Crontab 组件，可以支持秒级任务。

> 可用自 `>= v2.0.5`

## 注解

在 Swoft 中，定时任务的使用非常的简单，只需要使用相关注解定义你的任务类即可。

**@Scheduled()**

用于声明定时任务，如果是声明定时任务类，则必须使用此注解

参数

* `name`  任务类的名称，为空则为此类的完整 `namespace`路径。

使用示例：`@Scheduled()`、`@Scheduled("taskName")`、`@Scheduled(name="taskName")`

**@Cron()**

声明需要运行的方法，如果没有使用此注解，则该方法不会被运行。

参数

* `value` 任务的 Crontab 表达式，支持到秒

使用示例： `@Cron("* * * * * *")`、`@Cron(value="* * * * * *")`，表达式可简写，例如一个每秒都要执行的任务则可定义为 `@Cron("*")`

## Cron格式说明

```
*    *    *    *    *    *
-    -    -    -    -    -
|    |    |    |    |    |
|    |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
|    |    |    |    +----- month (1 - 12)
|    |    |    +------- day of month (1 - 31)
|    |    +--------- hour (0 - 23)
|    +----------- min (0 - 59)
+------------- sec (0-59)
```

示例：

1. `* * * * * *` 表示每秒执行一次。
2. `0 * * * * *` 表示每分钟的第0秒执行一次，即每分钟执行一次。
3. `0 0 * * * *` 表示每小时的0分0秒执行一次，即每小时执行一次。
4. `0/10 * * * * *` 表示每分钟的第0秒开始每10秒执行一次。
5. `10-20 * * * * *` 表示每分钟的第10-20秒执行一次。
6. `10,20,30 * * * * *` 表示每分钟的第10,20,30秒各执行一次。

## 声明定时任务

在 Swoft 中使用定时任务相当的简单，只需两步操作，`声明定时任务`和`配置启用`，这两部操作都相当的简单，我们先来看声明任务。

```php
<?php declare(strict_types=1);

namespace App\Crontab;

use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;

/**
 * Class CronTask
 *
 * @since 2.0
 *
 * @Scheduled()
 */
class CronTask
{
    /**
     * @Cron("* * * * * *")
     */
    public function secondTask()
    {
        printf("second task run: %s ", date('Y-m-d H:i:s', time()));
    }

    /**
     * @Cron("0 * * * * *")
     */
    public function minuteTask()
    {
        printf("minute task run: %s ", date('Y-m-d H:i:s', time()));
    }

}
```

## 配置启用

定时任务的执行是基于 Swoft 的 [进程](process/index.md),所以我们需要和使用 [用户进程](process/user-process.md)  的方式一样在配置中启用 Crontab 组件的自定义进程即可。

```php
 return [
    'httpServer'     => [
            // ...
            'process' => [
                'crontab' => bean(Swoft\Crontab\Crontab::class)
            ],
            // ...
        ],
 ];   
```
如上我们就配置成功了服务启动后，我们的定时任务进程也会随之启动

## 直接调用执行定时任务

除了定时执行我们设置好的任务外，我们还可以在业务代码中直接手动执行我们的定时任务，方法如下。

```php
    $crontab = BeanFactory::getBean("crontab");
    $crontab->execute("testCrontab", "method");
```

通过 Bean 容器拿到 crontab 管理器，然后直接使用 `execute($beanName,$methodName)` 方法，此方法有两个参数,`$beanName` 就是传入在 `@Scheduled()` 注解中设置的名字，`$methodName` 则是传入 `@Scheduled()` 标注的类中，`@Cron()` 标注的方法。
