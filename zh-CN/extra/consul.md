# Consul

Consul可以用来实现分布式系统的服务发现与配置，它是HashiCorp公司推出的一款实用开源工具，支持Linux等平台。Consul是分布式的、高可用的、可横向扩展的。swoft-consul 组件，整合了 consul 功能，开发者可以直接通过该组件使用 consul 功能。

## 安装

```php
composer require swoft/consul
```

## 使用

本章以操作 consul KV 为例，首先必须配置(启动) swoft-consul 组件


### 配置

配置如下：

app/bean.php 文件中配置：

```php
return [
    // ...
    'consul' => [
        'host' => '192.168.4.11'
    ]
    
    // ...
];
```

详细配置

- host consul 地址IP
- port consul 端口号
- timeout 请求超时时间



### 实例

```php
<?php declare(strict_types=1);


namespace App\Model\Logic;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Consul\Agent;
use Swoft\Consul\Catalog;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Consul\Health;
use Swoft\Consul\KV;
use Swoft\Consul\Session;

/**
 * Class ConsulLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class ConsulLogic
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @Inject()
     *
     * @var Health
     */
    private $health;

    /**
     * @Inject()
     *
     * @var Catalog
     */
    private $catalog;

    /**
     * @Inject()
     *
     * @var KV
     */
    private $kv;

    /**
     * @Inject()
     *
     * @var Session
     */
    private $session;


    /**
     * @throws ReflectionException
     * @throws ContainerException
     * @throws ClientException
     * @throws ServerException
     */
    public function kv(): void
    {
        $value = 'value content';
        $this->kv->put('/test/my/key', $value);

        $response = $this->kv->get('/test/my/key');
        var_dump($response->getBody(), $response->getResult());
    }
}
```

consul 所有操作返回都是 `Swoft\Consul\Response` 对象，该对象提供如下方法

- getHeaders() 请求返回的 headers
- getBody() 请求返回原始 body 信息
- getStatusCode() 请求返回 http code
- getResult() 请求数据通过 json_decode 格式化的数据

> 可以直接注入 agent / health / catalog / kv / session 对象简单灵活，实际业务按需要注入相应对象操作，注意命名空间要引用正确

## 方法列表

### agent

- checks()
- services()
- members(array $options = [])
- self()
- join(string $address, array $options = [])
- forceLeave(string $node)
- registerCheck(array $check)
- deregisterCheck(string $checkId)
- passCheck(string $checkId, array $options = [])
- warnCheck(string $checkId, array $options = [])
- failCheck(string $checkId, array $options = [])
- registerService(array $service)
- deregisterService(string $serviceId)

### health

- node(string $node, array $options = [])
- checks(string $service, array $options = [])
- service(string $service, array $options = [])
- state(string $state, array $options = [])

### catalog

- register(array $node)
- deregister(array $node)
- datacenters()
- nodes(array $options = [])
- node(string $node, array $options = [])
- services(array $options = [])
- service(string $service, array $options = [])

### kv

- get(string $key, array $options = [])
- put(string $key, string $value, array $options = [])
- delete(string $key, array $options = [])

### session

- create(array $body = null, array $options = [])
- destroy(string $sessionId, array $options = [])
- info(string $sessionId, array $options = [])
- node(string $node, array $options = [])
- all(array $options = [])
- renew(string $sessionId, array $options = [])
