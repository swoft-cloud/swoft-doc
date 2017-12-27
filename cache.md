# 缓存

缓存目前只支持 Redis, Redis 使用有两种方式直接调用和延迟收包调用。

## Redis 配置

缓存连接池支持两种方式配置，config/properties/cache.php或.env环境文件中配置。如果两者都有，.env配置会覆盖config/properties/cache.php里面配置

### properties配置

配置文件路径config/properties/cache.php

```php
return [
    'redis' => [
        'name' => 'redis',
        "uri"         => [
            '127.0.0.1:6379',
            '127.0.0.1:6379',
        ],
        "maxIdel"     => 8,
        "maxActive"   => 8,
        "maxWait"     => 8,
        "timeout"     => 8,
        "balancer"    => 'random',
        "useProvider" => false,
        'provider'    => 'consul',
    ],
];
```

### .env配置

.env格式，可以从.env.example里面复制，再修改成配置信息。

```
# the pool of redis
REDIS_NAME=redis
REDIS_URI=127.0.0.1:6379,127.0.0.1:6379
REDIS_MAX_IDEL=6
REDIS_MAX_ACTIVE=10
REDIS_MAX_WAIT=20
REDIS_TIMEOUT=200
REDIS_USE_PROVIDER=false
REDIS_BALANCER=random
REDIS_PROVIDER=consul
```

## Redis 使用

RedisClient 的方法和 PHP Redis 扩展方法是一一致的。唯一不一样的是提供直接调用和延迟收包调用\(用于并发\)

```php
// 直接调用
RedisClient::set('name', 'redis client stelin', 180);
$name = RedisClient::get('name');
RedisClient::get($name);

// 延迟收包调用
$ret = RedisClient::deferCall('get', ['name']);
$ret2 = RedisClient::deferCall('get', ['name']);

$result = $ret->getResult();
$result2 = $ret2->getResult();

$data = [
    'redis' => $name,
    'defer' => $result,
    'defer2' => $result2,
];
```



