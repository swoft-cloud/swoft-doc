# 定义定时任务

## 说明

在框架`App/Task`目录下，可以定义任务类，定义规则如下：

1. 必须使用@Task注解标明这是一个任务实体
2. 定时执行任务方法上必须使用@Scheduled(cron="\* \* \* \* \* \*")注解标明这上一个定时任务，并且附上crontab规则。

当任务到达触发点的时候，系统会将任务投递到异步任务进行处理，为swoft系统提供可以稳定不阻塞的并行任务执行操作。

## 定义任务

```php
<?php
namespace App\Tasks;

use Swoft\Bean\Annotation\Task;

/**
 * 定时任务类
 *
 * @uses      CrontabTask
 * @Task("mycrontab")
 * @version   2017年11月1日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CrontabTask
{
    // some other task

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
    public function cronTask2()
    {
        echo time() . "第3-5秒执行\n";
        return 'cron';
    }
}

```
