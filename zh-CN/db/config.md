# 数据库配置

主要是配置数据库主从连接信息，Swoft 提供properties和env 两种方式配置，且env会覆盖properties配置。主从都配置，默认读操作使用从配置，写操作使用主配置，若配置主，读写操作都会使用主配置。


## properties

配置app/config/properties/db.php

```php
return [
    'master' => [
        'name'        => 'master',
        'uri'         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        ],
        'maxIdel'     => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
    ],
    'slave' => [
        'name'        => 'slave',
        'uri'         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        ],
        'maxIdel'     => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
    ],
];
```
- master/slave 主从配置
- name 连接池节点名称，用于服务发现
- uri 连接地址信息
- maxIdel 最大空闲连接
- maxActive 最大活跃连接
- maxWait 最大等待连接
- timeout 超时时间，单位秒

# env

配置.env文件

```
# the pool of master nodes pool
DB_NAME=dbMaster
DB_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_MAX_IDEL=6
DB_MAX_ACTIVE=10
DB_MAX_WAIT=20
DB_TIMEOUT=200

# the pool of slave nodes pool
DB_SLAVE_NAME=dbSlave
DB_SLAVE_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_SLAVE_MAX_IDEL=6
DB_SLAVE_MAX_ACTIVE=10
DB_SLAVE_MAX_WAIT=20
DB_SLAVE_TIMEOUT=200
```

- DB/DB_SLAVE_NAME 连接池节点名称，用于服务发现
- DB/DB_SLAVE_URI 连接地址信息
- DB/DB_SLAVE_MAX_IDEL 最大空闲连接
- DB/DB_SLAVE_MAX_ACTIVE 最大活跃连接
- DB/DB_SLAVE_MAX_WAIT 最大等待连接
- DB/DB_SLAVE_TIMEOUT 超时时间，单位秒

