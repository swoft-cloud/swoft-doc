# 服务注册与发现

微服务治理过程中，经常会涉及注册启动的服务到第三方集群，比如 consul / etcd 等等，本章以 Swoft 框架中使用 swoft-consul 组件，实现服务注册与发现为例。

## 服务注册

无论是 http / rpc / ws 服务，启动的时候只需监听 `SwooleEvent::START` 事件，即可把启动的服务注册到第三方集群。

> 提示：在这里当前服务是一个服务提供者，比如 用户服务、商品服务。

### 注册服务

本章这里以启动 http server 注册服务为例：

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


        // Register
        $this->agent->registerService($service);
        CLog::info('Swoft http register service success by consul!');
    }
}
```

详细流程：

- `$event->getTarget()` 获取的对象是启动服务的对象，比如启动的 http 拿到的就是  `Swoft\Http\Server\HttpServer` 对象
- target 对象里面可以获取启动服务的所有信息包括配置信息
- 根据服务配置信息和业务实际情况，注册服务信息到 consul

> 必须使用 `Swoole\Coroutine\Scheduler` 否则无法注册服务，swoole 4.4+ 支持该功能


### 取消服务

服务启动注册服务，服务关闭或者退出则需要取消服务注册，此时这里和注册一样监听一个 `SwooleEvent::SHUTDOWN` 事件即可，本章这里还是以 取消 Http server 服务为例：

```php
<?php declare(strict_types=1);


namespace App\Listener;

use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class DeregisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(SwooleEvent::SHUTDOWN)
 */
class DeregisterServiceListener implements EventHandlerInterface
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

        $this->agent->deregisterService('swoft');
    }
}    
```

详细流程：

- `$event->getTarget()` 获取的对象是启动服务的对象，比如启动的 http 拿到的就是  `Swoft\Http\Server\HttpServer` 对象
- target 对象里面可以获取启动服务的所有信息包括配置信息
- 根据服务配置信息和业务实际情况，取消已经支持的服务

> 必须使用 `Swoole\Coroutine\Scheduler` 否则无法注册服务，swoole 4.4+ 支持该功能


## 服务发现

本章这里以 Rpc client 为例，通过第三方集群 consul 下发可用的服务(eg: 用户服务、商品服务)列表。

首先定义服务提供者，一个 provider 只提供一个服务，只是有多个地址。

### 代码示例

```php
<?php declare(strict_types=1);

namespace App\Common;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Consul\Agent;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Rpc\Client\Client;
use Swoft\Rpc\Client\Contract\ProviderInterface;

/**
 * Class RpcProvider
 *
 * @since 2.0
 *        
 * @Bean()
 */
class RpcProvider implements ProviderInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param Client $client
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws ClientException
     * @throws ServerException
     * @example
     * [
     *     'host:port',
     *     'host:port',
     *     'host:port',
     * ]
     */
    public function getList(Client $client): array
    {
        // Get health service from consul
        $services = $this->agent->services();

        $services = [

        ];

        return $services;
    }
}
```

详细流程：

- 实现 `Swoft\Rpc\Client\Contract\ProviderInterface` 接口
- 根据 $client 参数获取当前 RPC 服务信息
- 根据服务信息，到第三方集群(consul) 里面查询可用服务
- 返回一个规定格式数组
- 此类，必须用 `@Bean` 标记为一个 bean 对像 


>  db / redis 也是支持这种方式发现可用的服务，但是需要实现其相应的接口。

### 配置服务

现在我们有了服务提供者，接下来将其配置到对应的 RPC 服务上面(`app/bean.php`):

```php
return [
    'user'           => [
      'class'   => ServiceClient::class,
      'host'    => '127.0.0.1',
      'port'    => 18307,
      'setting' => [
          'timeout'         => 0.5,
          'connect_timeout' => 1.0,
          'write_timeout'   => 10.0,
          'read_timeout'    => 0.5,
      ],
      'packet'  => bean('rpcClientPacket'),
      'provider' => bean(RpcProvider::class)
    ],
    'user.pool'      => [
      'class'  => ServicePool::class,
      'client' => bean('user')
    ],  
];
```

- 这里在 user 服务上，通过 `provider` 参数注入了一个服务提供者 `RpcProvider::class` (bean 名称)

