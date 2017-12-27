# 配置

数据库连接池支持两种方式配置，config/properties/db.php或.env环境文件中配置。如果两者都有，.env配置会覆盖config/properties/db.php里面配置。连接池名称，可用于指定连接池操作数据。

## properties配置

## .env配置

配置文件路径config/properties/db.php

```php
return [
    'master' => [
        'name' => 'master',
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

    'slave' => [
        'name' => 'slave',
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
# the pool of master nodes pool
DB_NAME=dbMaster
DB_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_MAX_IDEL=6
DB_MAX_ACTIVE=10
DB_MAX_WAIT=20
DB_TIMEOUT=200
DB_USE_PROVIDER=false
DB_BALANCER=random
DB_PROVIDER=consul

# the pool of slave nodes pool
DB_SLAVE_NAME=dbSlave
DB_SLAVE_URI=127.0.0.1:3306/test?user=root&password=123456&charset=utf8,127.0.0.1:3306/test?user=root&password=123456&charset=utf8
DB_SLAVE_MAX_IDEL=6
DB_SLAVE_MAX_ACTIVE=10
DB_SLAVE_MAX_WAIT=20
DB_SLAVE_TIMEOUT=200
DB_SLAVE_USE_PROVIDER=false
DB_SLAVE_BALANCER=random
DB_SLAVE_PROVIDER=consul
```



