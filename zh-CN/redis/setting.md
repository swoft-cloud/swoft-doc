# Redis 设置

* 1 [基础配置](#基础配置)  
* 2 [详细配置](#详细配置)
* 3 [Redis集群配置](#Redis集群配置)
* 4 [Redis连接池配置](#Redis连接池配置)
* 5 [实现predis](#实现predis)

## 基础配置

Swoft 应用的 Redis 配置都在配置文件 app/bean.php 中。在这个文件里，你可以看到 redis 数组里面包含了应用程序使用的 Redis 服务器：

```php
'redis'      => [
    'class'    => RedisDb::class,
    'driver'   => 'phpredis',
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'database' => 0,
],
```
默认的服务器配置应该足以进行开发。当然，你也可以根据使用的环境来随意更改这个数组。只需在配置文件中给每个 Redis 服务器指定名称、`host` 和 `port` 即可。

- class 指定当前配置驱动类型
- 使用那一个 redis 驱动 默认是 `phpredis` 你也可以换成 `predis` 具体看 [实现predis](#实现predis)
- host 连接地址 默认 `127.0.0.1`
- port 端口 默认 `6379`
- database 缓存数据库index 默认 `0`

## 详细配置
```php
 'redis'      => [
        'class'         => RedisDb::class,
        'host'          => '127.0.0.1',
        'port'          => 6379,
        'database'      => 0,
        'retryInterval' => 10,
        'readTimeout'   => 0,
        'timeout'       => 2,
        'option'        => [          
            'prefix'      => 'xxx',
            'serializer' => Redis::SERIALIZER_PHP
        ],
    ],
```
- retryInterval  重试间隔
- readTimeout 读取超时时间 0：不超时，单位秒
- timeout 超时时间 0：不超时，单位秒
- option phpredis 的其他配置
    - prefix redis 前缀
    - serializer 序列化支持三种选项 默认是没有序列化的
        - Redis::SERIALIZER_NONE 不序列化数据
        - Redis::SERIALIZER_PHP  使用php内置序列化/反序列化
        - Redis::SERIALIZER_IGBINARY  使用igBinary序列化/反序列化
        
## Redis集群配置

集群配置和基础配置有点区别都是在同一文件下，配置了 clusters 属性了普通配置就失效了，连接池默认也会创建集群的连接

```php

'redis' => [
    'class'    => \Swoft\Redis\RedisDb::class,
    'option'   => [
        'timeout'    => 3,
        'persistent' => true,
    ],
    'clusters' => [
        [
            'host'         => '127.0.0.1',
            'prefix'       => 'swoft-t',
            'read_timeout' => 1,
            'port'         => 6379,
            'database'     => 1,
            'password'     => 123445,
        ],
    ]
]
```

- option 集群配置
    - timeout 超时时间 默认 0 单位秒
    - persistent 是否持久化 默认 false
- clusters redis clusters 节点二维数组方法配置
    - host 节点地址
    - port 节点端口
    - password 节点密码
    - read_timeout 读取超时时间
    - database 节点的 缓存数据库index

集群没有序列化配置

## Redis连接池配置

Swoft 所有连接池配置差不多，如果你想使用不同的 database 或者不同节点。
那么就很适合定义连接池，配置都在 `app\bean.php`里面。

如果是集群的话可以这样做：
```php
 'redis-clusters' => [
    'class'    => \Swoft\Redis\RedisDb::class,
    'option'   => [
        'timeout'    => 3,
        'persistent' => true,
    ],
    'clusters' => [
        [
            'host'         => '127.0.0.1',
            'prefix'       => 'swoft-t',
            'read_timeout' => 1,
            'port'         => 6379,
            'database'     => 1,
            'password'     => 123445,
        ],
    ]
],
'redis.clusters-1'     => [
    'class'   => \Swoft\Redis\Pool::class,
    'redisDb' => \bean('redis-clusters'),
    'minActive'   => 100,
    'maxActive'   => 200,
    'maxWait'     => 0,
    'maxWaitTime' => 0,
    'maxIdleTime' => 40,
],
```

普通 redis
```php
'redis-2'      => [
    'class'         => RedisDb::class,
    'host'          => '127.0.0.2',
    'port'          => 6378,
    'database'      => 1,
    'retryInterval' => 10,
    'readTimeout'   => 0,
    'timeout'       => 2,
    'option'        => [          
        'prefix'      => 'xxx',
        'serializer' => Redis::SERIALIZER_PHP
    ],
],
'redis.pool.2'     => [
    'class'   => \Swoft\Redis\Pool::class,
    'redisDb' => \bean('redis-2'),
    'minActive'   => 100,
    'maxActive'   => 300,
    'maxWait'     => 0,
    'maxWaitTime' => 0,
    'maxIdleTime' => 60,
],

```

- redis.clusters-1 连接池名称自定义
    - class 连接池驱动类 一般不用改，如果需要可以继承写成自己的 `Pool` 类也是可以的
    - redisDb 指定当前连接使用哪个一个 redisDb配置， 例子中替换自己定义的。
    - minActive 连接池需要维持的连接数
    - maxActive 连接最大保持的连接数
    - maxWait   连接最多等待连接数, 如果没有限制为0(默认)
    - maxWaitTime 连接最大等待时间，单位秒，如果没有限制为0(默认)
    - maxIdleTime 连接最大空闲时间，单位秒
    
使用 redis.clusters-1 连接池 ：
```php
Redis::connection('redis.clusters-1')->get($key);
```
你可以可以将默认的连接池替换成自己的
```php
'redis.pool'     => [
    'class'   => \Swoft\Redis\Pool::class,
    'redisDb' => \bean('redis.clusters-1')
]
```
`redis.pool` 是默认的连接池名称 把连接池里面的redisDb 属性替换成自己定义的就可以了
使用就和正常一样了 例如：
```php
Redis::set($key, "[]");
```
## 实现predis

如果使用predis 需要实现两个契约 `Swoft\Redis\Contract\ConnectorInterface`和`Swoft\Redis\Contract\ConnectionInterface`。推荐使用 `phpredis` 因为它更高效率

参考 `PhpRedisConnection` 和 `PhpRedisConnector` 的 实现，实现完毕 
在 配置中的 `redis` 中的` 'driver'   => 'phpredis'` 替换成 `predis` 即可，
