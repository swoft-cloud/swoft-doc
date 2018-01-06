# 缓存

缓存目前只支持 Redis, Redis 使用有两种方式直接调用和延迟收包调用。

## 缓存管理配置

配置文件config/beans/base.php
```php
return [
    // ...
    'cache' => [
        'class' => \Swoft\Cache\Cache::class,
        'driver' => 'redis', // 定义默认驱动
        'drivers' =>[ // 定义自定义驱动集合，根据key使用
            'redis' => \Swoft\Cache\Redis\CacheRedis::class 
        ]
    ]
    // ...
];
```

## 缓存配置

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
        'db'          => 1,
    ],
];
```

### .env配置

.env格式，可以从.env.example里面复制，再修改成配置信息。

```
# the pool of redis
REDIS_NAME=redis
REDIS_DB=2
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

cache 的方法和 PHP Redis 扩展方法是一一致的。唯一不一样的是提供直接调用和延迟收包调用\(用于并发\)

```php
/**
 * @Controller(prefix="/redis")
 * @uses      RedisController
 * @version   2017-11-12
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisController
{
    /**
     * 通用缓存操作，根据默认驱动，底层自动切换 
     *
     * @Inject("cache")
     * @var Cache
     */
    private $cache;

    /**
     * 直接使用Redis缓存
     * 
     * @Inject()
     * @var CacheRedis
     */
    private $redis;

    public function testCache()
    {
        $result = $this->cache->set('name', 'stelin');
        $name   = $this->cache->get('name');

        return [$result, $name];
    }

    public function testRedis()
    {
        $result = $this->redis->set('nameRedis', 'stelin2');
        $name   = $this->redis->get('nameRedis');

        return [$result, $name];
    }

    public function testFunc()
    {
        $result = cache()->set('nameFunc', 'stelin3');
        $name   = cache()->get('nameFunc');

        return [$result, $name];
    }

    public function testFunc2()
    {
        // 全局函数操作，使用默认驱动，也可以自定驱动
        $result = cache()->set('nameFunc2', 'stelin3');
        $name   = cache('nameFunc2');
        $name2   = cache('nameFunc3', 'value3');

        return [$result, $name, $name2];
    }

    public function testDelete()
    {
        $result = $this->cache->set('name', 'stelin');
        $del    = $this->cache->delete('name');

        return [$result, $del];
    }

    public function clear()
    {
        $result = $this->cache->clear();

        return [$result];
    }

    public function setMultiple()
    {
        $result = $this->cache->setMultiple(['name6' => 'stelin6', 'name8' => 'stelin8']);
        $ary    = $this->cache->getMultiple(['name6', 'name8']);

        return [$result, $ary];
    }

    public function deleteMultiple()
    {
        $result = $this->cache->setMultiple(['name6' => 'stelin6', 'name8' => 'stelin8']);
        $ary    = $this->cache->deleteMultiple(['name6', 'name8']);

        return [$result, $ary];
    }

    public function has()
    {
        $result = $this->cache->set("name666", 'stelin666');
        $ret    = $this->cache->has('name666');

        return [$result, $ret];
    }

    public function testDefer()
    {
        $ret1 = $this->redis->deferCall('set', ['name1', 'stelin1']);
        $ret2 = $this->redis->deferCall('set', ['name2', 'stelin2']);

        $r1 = $ret1->getResult();
        $r2 = $ret2->getResult();

        $ary = $this->redis->getMultiple(['name1', 'name2']);

        return [$r1, $r2, $ary];
    }
}
```



