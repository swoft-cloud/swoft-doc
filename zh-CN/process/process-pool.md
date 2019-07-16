# 进程池

进程池一般用于需要程序一直运行的场景，比如队列消费，数据计算。Swoft 框架中，基于 Swoole 进程池模型再次封装，便于开发者快速简单的使用进程池。

> 2.0.4+ 支持且需要安装 [swoft-process](index.md) 组件

## 配置

组件安装成功后，默认不需要配置也可以使用，配置如下：

app/bean.php

```php
return [
    'processPool' => [
        'class' => ProcessPool::class,
        'workerNum' => 3
    ]
];
```

详细参数如下：

- `workerNum` worker 进程数
- `ipcType`  IPC类型
- `coroutine` 是否开启协程，默认是开启

<p class="tip"> Swoft 框架中必须是协程模式运行，协程模式运行下，可以使用 Swoft 封装的所有 IO 操作，以及其它非 Swoft 协程操作。</p>


## 声明工作进程

配置好之后，就是声明工作进程。如下已 workerNum =3，定义三个 worker 进程为例：

worker 进程1：

```php
<?php declare(strict_types=1);


namespace App\Process;


use Swoft\Log\Helper\CLog;
use Swoft\Process\Annotation\Mapping\Process;
use Swoft\Process\Contract\ProcessInterface;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class Worker1Process
 *
 * @since 2.0
 *
 * @Process(workerId=0)
 */
class Worker1Process implements ProcessInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     */
    public function run(Pool $pool, int $workerId): void
    {
        while (true) {
            CLog::info('worker-' . $workerId);

            Coroutine::sleep(3);
        }
    }
}
```

- worker 进程必须实现 `Swoft\Process\Contract\ProcessInterface` 接口
- 开发者业务必须自己实现类似 `while(true)` 逻辑
- `@Process` 注解 `workerId=0` 表示第1个进程绑定这个处理逻辑流程


worker 进程2和进程3：

```php
<?php declare(strict_types=1);


namespace App\Process;


use App\Model\Entity\User;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Annotation\Mapping\Process;
use Swoft\Process\Contract\ProcessInterface;
use Swoft\Redis\Redis;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class Worker2Process
 *
 * @since 2.0
 *
 * @Process(workerId={1,2})
 */
class Worker2Process implements ProcessInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     *
     * @throws DbException
     */
    public function run(Pool $pool, int $workerId): void
    {
        while (true) {

            // Database
            $user = User::find(1)->toArray();
            CLog::info('user='.json_encode($user));

            // Redis
            Redis::set('test', 'ok');
            CLog::info('test='.Redis::get('test'));

            CLog::info('worker-' . $workerId.' context='.context()->getWorkerId());

            Coroutine::sleep(3);
        }
    }
}
```

- worker 进程必须实现 `Swoft\Process\Contract\ProcessInterface` 接口
- 开发者业务必须自己实现类似 `while(true)` 逻辑
- `@Process` 注解 `workerId={1,2}` 表示第2个进程和第3个进程，同时绑定这个处理逻辑流程

> `workerId` 绑定ID 是从 0 开始算起, workerId 如果不写默认情况，当前程序流程绑定到其它未绑定的进程。

### @Process

标记类是一个进程池处理流程

- workerId 绑定的进程ID,可以使单个或者数组，默认情况，是绑定到其它未绑定的进程。

## 运行

配置和声明工作进程完成后，就是启动进程池，启动进程池和其它服务启动很类似

非后台启动:

```
php bin/swoft process:start
```

后台启动：

```
 php bin/swoft http:start -d
```

重启所有 worker 进程：

```
 php bin/swoft http:reload
```

重新启动：

```
 php bin/swoft http:restart
```