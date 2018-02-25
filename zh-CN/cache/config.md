# 缓存配置

缓存配置主要包括驱动配置和连接池配置。

## 驱动配置

缓存默认配置是redis，用户也可以在app/config/beans/base.php文件里面新增配置信息。

```php
return [
    // ......
    'cache'            => [
        'class' => \Swoft\Cache\Cache::class,
        'driver' => 'xxx',
        'drivers' => [
            'xxx' => \Swoft\Xxx::class
        ]
    ]
];
```

- class 可有可以无的，框架底层已经配置
- driver 当前缓存使用哪个驱动，默认是redis
- drivers 配置自定义驱动，key是驱动名称

## 连接池配置
连接池配置有properties和env两种方式，但是evn配置会覆盖properties。

### properties
app/config/properties/cache.php配置文件

```php
return [
    'redis' => [
        'name'        => 'redis',
        'uri'         => [
            '127.0.0.1:6379',
            '127.0.0.1:6379',
        ],
        'maxIdel'     => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
        'db'          => 1,
        'serialize'   => 0,
    ],
];
```
- name 连接池节点名称，用于服务发现
- uri 连接地址信息
- maxIdel 最大空闲连接
- maxActive 最大活跃连接
- maxWait 最大等待连接
- timeout 超时时间，单位秒
- serialize 是否序列化
- db 缓存数据库index

### env

.env配置文件
```
REDIS_NAME=redis
REDIS_DB=2
REDIS_URI=127.0.0.1:6379,127.0.0.1:6379
REDIS_MAX_IDEL=6
REDIS_MAX_ACTIVE=10
REDIS_MAX_WAIT=20
REDIS_TIMEOUT=200
REDIS_SERIALIZE=1
```

- REDIS_NAME 连接池节点名称，用于服务发现
- REDIS_URI 连接地址信息
- REDIS_MAX_IDEL 最大空闲连接
- REDIS_MAX_ACTIVE 最大活跃连接
- REDIS_MAX_WAIT 最大等待连接
- REDIS_TIMEOUT 超时时间，单位秒
- REDIS_SERIALIZE 是否序列化
- REDIS_DB 缓存数据库index

