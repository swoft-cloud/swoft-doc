# Redis
Redis操作提高多种灵活方式，全局函数和对象操作方式，同时也支持延迟收包。

## 全局函数

通过全局函数操作缓存

```php
/**
 * @Controller(prefix="/redis")
 */
class RedisController
{
   
    public function testFunc()
    {
        $result = cache()->set('nameFunc', 'stelin3');
        $name   = cache()->get('nameFunc');

        return [$result, $name];
    }

    public function testFunc2()
    {
        $result = cache()->set('nameFunc2', 'stelin3');
        $name   = cache('nameFunc2');
        $name2   = cache('nameFunc3', 'value3');

        return [$result, $name, $name2];
    } 
}
```

- cache(string $key = null, $default = null) 如果参数都为默认值，会返回Cache对象，提供缓存操作。如果传入key参数，返回get缓存值，如果key不存在，返回default参数值


## 对象方式

通过注入Cache和Redis(驱动对象)，都可以操作缓存，唯一不同的是，Cache方式注入的对象，是使用配置的默认驱动。

```php
/**
 * @Controller(prefix="/redis")
 */
class RedisController
{
    /**
     * @Inject("cache")
     * @var Cache
     */
    private $cache;

    /**
     * @Inject()
     * @var \Swoft\Redis\Redis
     */
    private $redis;

    public function testCache()
    {
        $result = $this->cache->set('name', 'stelin');
        $name   = $this->cache->get('name');

        $this->redis->incr("count");

        $this->redis->incrBy("count2", 2);

        return [$result, $name, $this->redis->get('count'), $this->redis->get('count2')];
    }
}
```

- Cache和Redis(驱动对象)对象，操作方式都一样，里面提供了一系列的缓存操作函数。

## 延迟收包
延迟收包可以实现多IO并发执行，不仅仅是多个缓存IO，也可以mysql/rpc/http混合并发请求，有效的提升执行性能。

```php
/**
 * @Controller(prefix="/redis")
 */
class RedisController
{
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

- 暂时只能提供一个统一的函数deferCall，实现延迟收包操作，接下来会新增deferXxx函数，实现对应延迟收包操作函数。