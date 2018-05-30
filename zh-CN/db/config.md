# 数据库配置

主要是配置数据库主从连接信息，Swoft 提供properties和env 两种方式配置，且env会覆盖properties配置。主从都配置，默认读操作使用从配置，写操作使用主配置，若配置主，读写操作都会使用主配置。


## properties

配置 `app/config/properties/db.php`

```php
return [
    'master' => [
        'name'        => 'master',
        'uri'         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
        'maxIdleTime' => 60,
        'maxWaitTime' => 3,
    ],
    'slave' => [
        'name'        => 'slave',
        'uri'         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
        'maxIdleTime' => 60,
        'maxWaitTime' => 3,
    ],
];
```

- master/slave 主从配置
- name 连接池节点名称，用于服务发现
- uri 连接地址信息
- minActive 最小活跃链接数
- maxActive 最大活跃连接数
- maxIdleTime 连接最大空闲时间，单位秒
- maxWaitTime 连接最大等待时间，单位秒
- maxWait 最大等待连接
- timeout 超时时间，单位秒

> master,slave 是两个特殊的名称，他们会归纳到 `default` 实例中去。表现为 `default.master`, `default.slave`

## env

配置.env文件

```
# the pool of master nodes pool
DB_NAME=dbMaster
DB_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_MIN_ACTIVE=6
DB_MAX_ACTIVE=10
DB_MAX_WAIT=20
DB_MAX_IDLE_TIME=60
DB_MAX_WAIT_TIME=3
DB_TIMEOUT=200

# the pool of slave nodes pool
DB_SLAVE_NAME=dbSlave
DB_SLAVE_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_SLAVE_MIN_ACTIVE=5
DB_SLAVE_MAX_ACTIVE=10
DB_SLAVE_MAX_WAIT=20
DB_SLAVE_MAX_WAIT_TIME=3
DB_SLAVE_MAX_IDLE_TIME=60
DB_SLAVE_TIMEOUT=200
```

- DB/DB_SLAVE_NAME 连接池节点名称，用于服务发现
- DB/DB_SLAVE_URI 连接地址信息
- DB/DB_SLAVE_MIN_ACTIVE 最小活跃链接数
- DB/DB_SLAVE_MAX_ACTIVE 最大活跃连接数
- DB/DB_SLAVE_MAX_IDLE_TIME 连接最大空闲时间，单位秒
- DB/DB_SLAVE_MAX_WAIT_TIME 连接最大等待时间，单位秒
- DB/DB_SLAVE_MAX_WAIT 最大等待连接
- DB/DB_SLAVE_TIMEOUT 超时时间，单位秒

## 数据库实例

上面的配置都是属于默认实例 `default`, 含有两个节点 `master` `slave`

### 增加实例

增加实例需在 `db.php` 增加新的实例配置，如下：

- 新增实例 `other`
- 它同样含有两个节点

```php
return [
    // ...
    'other' => [
        'master' => [
            'name'        => 'master2',
            'uri'         => [
                '127.0.0.1:3301',
                '127.0.0.1:3301',
            ],
            'maxIdel'     => 1,
            'maxActive'   => 1,
            'maxWait'     => 1,
            'timeout'     => 1,
        ],

        'slave' => [
            'name'        => 'slave3',
            'uri'         => [
                '127.0.0.1:3301',
                '127.0.0.1:3301',
            ],
            'maxIdel'     => 1,
            'maxActive'   => 1,
            'maxWait'     => 1,
            'timeout'     => 1,
        ],
    ],
];
```

