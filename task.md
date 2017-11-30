# 任务

任务主要用于处理一些费时的工作，任务有异步任务和协程任务，异步任务投递成功，立即返回，无需等待结果。协程任务，投递任务后，让出当前协程，处理成功后，切回当前协程，继续后续逻辑处理。

## 定义任务

> @Task注解定义一个任务类，并且可以指定任务名称
>
> 类里面的每一个方法就是一个任务
>
> 每个任务方法里面都可以使用日志、rpc、http、mysql、redis底层无缝自动切换成同步IO操作
>
> 任务里面可以直接使用model逻辑层
>
> @Scheduled注解可以实现crontab定义任务，目前开发中...
>
> 协程任务可以实现没有协程的客户端，比如mongodb....

```php
<?php

namespace App\Tasks;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use App\Models\Logic\IndexLogic;
use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Scheduled;
use Swoft\Bean\Annotation\Task;
use Swoft\Db\EntityManager;
use Swoft\Http\HttpClient;
use Swoft\Redis\Cache\RedisClient;
use Swoft\Service\Service;

/**
 * 测试task
 *
 * @Task("test")
 */
class TestTask
{
    /**
     * 逻辑层
     *
     * @Inject()
     * @var IndexLogic
     */
    private $logic;

    /**
     * 任务中,使用redis自动切换成同步redis
     *
     * @param mixed $p1
     * @param mixed $p2
     *
     * @return string
     */
    public function corTask($p1, $p2)
    {
        static $status = 1;
        $status++;
        echo "this cor task \n";
        App::trace("this is task log");
        //        RedisClient::set('name', 'stelin boy');
        $name = RedisClient::get('name');
        return 'cor' . " $p1" . " $p2 " . $status . " " . $name;
    }

    /**
     * 任务中使用mysql自动切换为同步mysql
     *
     * @return bool|\Swoft\Db\DataResult
     */
    public function testMysql()
    {
        $user = new User();
        $user->setName("stelin");
        $user->setSex(1);
        $user->setDesc("this my desc");
        $user->setAge(mt_rand(1, 100));

        $count = new Count();
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));

        $em = EntityManager::create();
        $em->beginTransaction();
        $uid = $em->save($user);
        $count->setUid(intval($uid));

        $result = $em->save($count);
        if ($result === false) {
            $em->rollback();
        } else {
            $em->commit();
        }
        $em->close();
        return $result;
    }

    /**
     * 任务中使用HTTP，自动切换成同步curl
     *
     * @return mixed
     */
    public function testHttp()
    {
        $requestData = [
            'name' => 'boy',
            'desc' => 'php'
        ];

        $result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
        $result2 = HttpClient::call("http://www.baidu.com/", HttpClient::GET, []);
        $data['result'] = $result;
        $data['result2'] = $result2;
        return $data;
    }

    /**
     * 任务中使用rpc,自动切换成同步TCP
     *
     * @return mixed
     */
    public function testRpc()
    {
        var_dump('^^^^^^^^^^^', ApplicationContext::getContext());
        App::trace("this rpc task worker");
        $result = Service::call("user", 'User::getUserInfo', [2, 6, 8]);
        return $result;
    }


    /**
     * 异步task
     *
     * @return string
     */
    public function asyncTask()
    {
        static $status = 1;
        $status++;
        echo "this async task \n";
        $name = RedisClient::get('name');
        App::trace("this is task log");
        return 'async-' . $status . '-' . $name;
    }

    /**
     * crontab定时任务，目前开发中...
     *
     * @Scheduled(cron="0 0/1 8-20 * * ?")
     */
    public function cronTask()
    {
        echo "this cron task  \n";
        return 'cron';
    }
}
```

## 投递任务

任务定义成功，可以调用Swoft\Task\Task类里面deliver方法投递任务，只需自定任务名称、方法、参数、任务类型\(协程或异步\)

```php
$result = Task::deliver('test', 'corTask', ['params1', 'params2'], Task::TYPE_COR);
$mysql = Task::deliver('test', 'testMysql', [], Task::TYPE_COR);
$http = Task::deliver('test', 'testHttp', [], Task::TYPE_COR, 20);
$rpc = Task::deliver('test', 'testRpc', [], Task::TYPE_COR, 5);
$result1 = Task::deliver('test', 'asyncTask', [], Task::TYPE_ASYNC);
```



