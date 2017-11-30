# 配置

orm配置很简单，只需配置数据库连接池`config/beans/db.php`，默认 dbMaster 和 dbSlave 连接池 ID 对应主从节点，也可以配置其他连接池 ID ，但是使用的时候，需要制定连接池 ID

```php
...

"dbMaster" => [
    "class"       => \Swoft\Pool\DbPool::class,
    // 数据库 DSNs
    "uri"         => [
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
    ],
    "maxIdel"     => 6,
    "maxActive"   => 10,
    "timeout"     => 200,
    "balancer"    => '${randomBalancer}',
    "serviceName" => 'user',
    "useProvider" => false,
    // 数据驱动
    'driver'      => \Swoft\Pool\DbPool::MYSQL
],
"dbSlave" => [
    "class"       => \Swoft\Pool\DbPool::class,
    "uri"         => [
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
    ],
    "maxIdel"     => 6,
    "maxActive"   => 10,
    "timeout"     => 200,
    "balancer"    => '${randomBalancer}',
    "serviceName" => 'user',
    "useProvider" => false,
    'driver'      => \Swoft\Pool\DbPool::MYSQL
],

...
```



