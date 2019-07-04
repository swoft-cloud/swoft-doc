# 服务注册与发现

微服务治理过程中，经常会涉及注册启动的服务到第三方集群，比如 consul / etcd 等等，本章以 Swoft 框架中使用 swoft-consul 组件，实现服务注册与发现为例。

## 服务注册

无论是 http / rpc / ws 服务，启动的时候只需监听 `SwooleEvent::START` 事件，即可启把启动的服务注册到第三方集群。

```php
<?php declare(strict_types=1);


namespace App\Listener;


use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class RegisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::START)
 */
class RegisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var HttpServer $httpServer */
        $httpServer = $event->getTarget();

        $service = [
            'ID'                => 'swoft',
            'Name'              => 'swoft',
            'Tags'              => [
                'http'
            ],
            'Address'           => '127.0.0.1',
            'Port'              => $httpServer->getPort(),
            'Meta'              => [
                'version' => '1.0'
            ],
            'EnableTagOverride' => false,
            'Weights'           => [
                'Passing' => 10,
                'Warning' => 1
            ]
        ];


        $scheduler = Swoole\Coroutine\Scheduler();
        $scheduler->add(function () use ($service) {
            // Register
            $this->agent->registerService($service);
            CLog::info('Swoft http register service success by consul!');
        });
        $scheduler->start();
    }
}
```

详细流程：

- `$event->getTarget()` 获取的对象是启动服务的对象，比如启动的 http 拿到的就是  `Swoft\Http\Server\HttpServer` 对象
- target 对象里面可以获取启动服务的所有信息包括配置信息
- 根据服务配置信息和业务实际情况，注册服务信息到 consul

> 必须使用 `Swoole\Coroutine\Scheduler` 否则无法注册服务，swoole 4.4+ 支持该功能



## 服务发现