# 配置

orm配置很简单，执行配置数据库连接池，默认dbMaster和dbSlave连接池ID对应主从节点，也可以配置其他连接池ID，但是使用的时候，需要制定连接池ID

```php
...

"dbMaster" => [
    "class"       => \Swoft\Pool\DbPool::class,
    "uri"         => [ // 数据库连接串
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
        '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
    ],
    "maxIdel"     => 6,
    "maxActive"   => 10,
    "timeout"     => 200,
    "balancer"    => '${randomBalancer}',
    "serviceName" => 'user',
    "useProvider" => false,
    'driver'      => \Swoft\Pool\DbPool::MYSQL // 数据驱动
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



