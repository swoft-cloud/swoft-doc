# Apollo

Apollo（阿波罗）是携程框架部门研发的分布式配置中心，能够集中化管理应用不同环境、不同集群的配置，配置修改后能够实时推送到应用端，并且具备规范的权限、流程治理等特性，适用于微服务配置管理场景。
Swoft 基于 Apollo 提供的 API，在之上进行封装，使之能在 Swoft 中快速使用。

## 安装

```php
composer require swoft/apollo
```

## 配置bean

下面以拉取 Apollo 命令空间为 `application` 配置为例：

首先在 `app/bean.php` 配置启用 Apollo

```php
return [
    // ...
    'apollo' => [
        'host'    => '192.168.2.102',
        'timeout' => 6
    ]
    
    // ...
];
```

### 参数详解

- host 地址
- port 端口号
- appId Apollo 应用ID
- clusterName Apollo 集群名称
- timeout 超时时间单位秒

<p class="tip"> 如果使用 apollo 监听更新回调函数，超时时间必须大于 60 秒 </p>

Apollo 配置完成后，像一个普通的 Bean 一样注入 Apollo `Swoft\Apollo\Config` 即可使用

## 使用

```php
<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class ApolloLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class ApolloLogic
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @throws ApolloException
     */
    public function pull(): void
    {
        $data = $this->config->pull('application');
        
        // Print data
        var_dump($data);
    }
}
```

以上就是一个简单的 Apollo 配置拉取，swoft-apollo 出此方法外，还提供了更多的使用方法。

## 方法列表

### pullWithCache

```php
public function pullWithCache(string $namespace, string $clientIp = ''): array
```

从 Apollo 缓存中拉取配置，大概有1秒的延迟


- $namespace 命名空间名称
- $clientIp 客户端IP，为空底层自动获取当前机器IP，用于灰度发布

### pull

```php
public function pull(string $namespace, string $releaseKey = '', string $clientIp = ''): array
```

实时拉取配置，没有延迟时间

- $namespace 命名空间名称
- $releaseKey 上一次拉取返回的版本号，更多描述，参考 Apollo 官方文档
- $clientIp 客户端IP，为空底层自动获取当前机器IP，用于灰度发布

### batchPull

```php
public function batchPull(array $namespaces, string $clientIp = ''): array
```
此方法是 `pull` 方法的一个批量封装，用于同时拉取多个 namespace 配置，但是不支持 `$releaseKey` 参数

- $namespaces 命名空间名称集合数组
- $clientIp 客户端IP，为空底层自动获取当前机器IP，用于灰度发布

### listen

```php
public function listen(array $namespaces, $callback, array $notifications = [], string $clientIp = ''): void
```

如果一直轮询拉取配置，会有很大一部分资源浪费，此方法就是解决该问题，当配置有更新的时候，会回调监听改变的函数

- $namespaces 命名空间名称集合数组
- $callback 配置改变监听函数，可以使闭包、对象方法都可以。
- $notifications 监听信息，详细描述参考 Apollo 官放文档
- $clientIp 客户端IP，为空底层自动获取当前机器IP，用于灰度发布


如果配置改变，监听函数回调的时候会传递一个参数，此参数信息记录了改变后的最新配置信息，比如：

```php
$callback = function(array $data){
    // ...
}
```
