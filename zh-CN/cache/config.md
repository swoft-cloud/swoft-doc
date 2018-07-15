# 缓存配置

缓存配置主要包括驱动配置和连接池配置。

## 驱动配置

缓存默认配置是redis，用户也可以在 `app/config/beans/base.php` 文件里面新增配置信息。

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
连接池配置有 properties 和 env 两种方式，但是 env 配置会覆盖 properties。

### properties

`app/config/properties/cache.php` 配置文件

```php
return [
    'redis' => [
        'name'        => 'redis',
        'uri'         => [
            'tcp://127.0.0.1:6379?auth=password',
            'tcp://127.0.0.1:6379',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'maxWaitTime' => 3,
        'maxIdleTime' => 60,
        'timeout'     => 8,
        'db'          => 1,
        'serialize'   => 0,
    ],
];
```

**参数说明：**

- name 连接池节点名称，用于服务发现
- uri 连接地址信息
- maxActive 最大活跃连接
- maxWait 最大等待连接
- minActive 最小活跃链接数
- maxIdleTime 连接最大空闲时间，单位秒
- maxWaitTime 连接最大等待时间，单位秒
- timeout 超时时间，单位秒
- serialize 是否序列化
- db 缓存数据库index

> TIPS: 当密码中含有特殊字符时，需先将密码部分urlencode一下，比如 `auth=W&AAA` 变换为 `auth=W%26AAA`

### env配置

.env 配置文件

```ini
REDIS_NAME=redis
REDIS_DB=2
REDIS_URI=127.0.0.1:6379,127.0.0.1:6379
REDIS_MIN_ACTIVE=5
REDIS_MAX_ACTIVE=10
REDIS_MAX_WAIT=20
REDIS_MAX_WAIT_TIME=3
REDIS_MAX_IDLE_TIME=60
REDIS_TIMEOUT=3
REDIS_SERIALIZE=1
```

- REDIS_NAME 连接池节点名称，用于服务发现
- REDIS_URI 连接地址信息
- REDIS_MIN_ACTIVE 最小活跃链接数
- REDIS_MAX_ACTIVE 最大活跃连接数
- REDIS_MAX_WAIT 最大等待连接
- REDIS_MAX_WAIT_TIME 连接最大等待时间，单位秒
- REDIS_MAX_IDLE_TIME 连接最大空闲时间，单位秒
- REDIS_TIMEOUT 超时时间，单位秒
- REDIS_SERIALIZE 是否序列化
- REDIS_DB 缓存数据库index