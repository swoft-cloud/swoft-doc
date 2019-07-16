# 用户进程

 Http/RPC/Websocket/TCP 等服务有些业务场景，需要一个后台运行进程去监控、上报或者其它特殊操作，此时可以在相应服务启动的时候，添加一个用户自定义工作进程，来实现。
 自定义用户进程与服务一起启动，服务关闭一起退出，如果自定义用户进程被意外关闭，服务会重新启动一个新的自定义用户进程，保证自定义用户进程一直存在。
 
 > 2.0.4+ 支持且需要安装 [swoft-process](index.md) 组件
 
 ## 声明用户进程
 
 使用自定义用户进程之前，必须定义用户进程，如下定义一个监控上报信息的用户进程为例：
 
 
自定义用户进程入口：
 
 ```php
<?php declare(strict_types=1);


namespace App\Process;


use App\Model\Logic\MonitorLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;

/**
 * Class MonitorProcess
 *
 * @since 2.0
 *
 * @Bean()
 */
class MonitorProcess extends UserProcess
{
    /**
     * @Inject()
     *
     * @var MonitorLogic
     */
    private $logic;

    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function run(Process $process): void
    {
        $this->logic->monitor($process);
    }
}
```

- 自定义用户进程必须实现 `Swoft\Process\UserProcess` 接口
- 自定义用户进程必须使用 `@Bean` 标记为一个 bean 对象


业务处理：

```php
<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Swoft\Redis\Redis;
use Swoole\Coroutine;

/**
 * Class MonitorProcessLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class MonitorLogic
{
    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function monitor(Process $process): void
    {
        $process->name('swoft-monitor');

        while (true) {
            $connections = context()->getServer()->getSwooleServer()->connections;
            CLog::info('monitor = ' . json_encode($connections));

            // Database
            $user = User::find(1)->toArray();
            CLog::info('user='.json_encode($user));

            // Redis
            Redis::set('test', 'ok');
            CLog::info('test='.Redis::get('test'));

            Coroutine::sleep(3);
        }
    }
}
```

<p class="tip"> 自定义用户进程里面，开发者必须实现类似 `while(true)` 的业务，且里面可以直接使用 Swoft 封装好的所有IO操作，比如数据库、缓存、RPC，以及其它非 Swoft 封装的协程操作 </p>
 
 ## 配置
 
 定义好了用户进程，必须配置才会有效，Http/RPC/Websocket/TCP 服务配置自定义进程都一样，这里以如上自定义的用户进程配置为例：
 
 配置自定义进程：
 
 ```php
 return [
    'httpServer'     => [
            'class'    => HttpServer::class,
            'port'     => 18306,
            'listener' => [
                'rpc' => bean('rpcServer')
            ],
            'process' => [
                'monitor' => bean(MonitorProcess::class)
            ],
            // ...
        ],
 ];   
 ```

这里以注入的方式配置了一个自定义用户进程，名称为 `monitor`
 
 
> 如果配置成功，服务启动后，用户进程里面的业务会自动执行，无需其它操作。