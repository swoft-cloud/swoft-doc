# 负载均衡

负载均衡主要是配合服务发现一起使用，暂时 Swoft 只支持随机策略，后续会提供更多选择。

## 配置
通常情况可以不用配置，系统已经配置了默认值。app/config/beans/base.php

```php
return [
     // ...
    'balancerSelector' => [
         'class' => \Swoft\Sg\BalancerSelector::class,
         'balancer' => 'random',
         'balancers' => [
             'random' => \Swoft\Sg\Balancer\RandomBalancer::class
         ]
     ],
     // ...
];
```

- balancer 定义默认负载类型
- balancers 配置定义的负载，通过KEY名字使用


## consul 负载均衡配置说明

>就用例子中user服务器举例说明，一台机器(192.168.7.197,此ip为内网ip)分别开启两台user服务器，端口分别为20000和20001

打开端口为20000服务器配置文件app/config/properties/provider.php配置如下：

```php
return [
    'consul' => [
        'address' => '127.0.0.1',
        'port'    => 8500,
        'register' => [
            'id'                => 'user',
            'name'              => 'user',
            'tags'              => [user],
            'enableTagOverride' => false,
            'service'           => [
                'address' => '192.168.1.197',
                'port'   => '20000',
            ],
            'check'             => [
                'id'       => 'user',
                'name'     => 'user',
                'tcp'      => '192.168.1.197:20000',
                'interval' => 10,
                'timeout'  => 1,
            ],
        ],
        'discovery' => [
            'name' => 'user',
            'dc' => 'dc',
            'near' => '',
            'tag' =>'',
            'passing' => true
        ]
    ],
];
```
打开端口为20001服务器配置文件app/config/properties/provider.php配置如下：
```php
return [
    'consul' => [
        'address' => '127.0.0.1',
        'port'    => 8500,
        'register' => [
            'id'                => 'user1',
            'name'              => 'user',
            'tags'              => [user1],
            'enableTagOverride' => false,
            'service'           => [
                'address' => '192.168.1.197',
                'port'   => '20000',
            ],
            'check'             => [
                'id'       => 'user1',
                'name'     => 'user',
                'tcp'      => '192.168.1.197:20000',
                'interval' => 10,
                'timeout'  => 1,
            ],
        ],
        'discovery' => [
            'name' => 'user',
            'dc' => 'dc',
            'near' => '',
            'tag' =>'',
            'passing' => true
        ]
    ],
];
```
- 前提条件，consul已经安装好了并启动，服务器已经开启了注册发现配置，配置开启请参考.env文件
- id 定义consul服务的唯一id
- name 定义consul服务名字，发现服务时会用到这个名字找对应服务，name是一对多，多服务器可以同名不同tags实现负载均衡
- tags 服务标记，多台同name服务时，可以用tags来区分
- 注意，discovery.tag 如果负载均衡时， 请配置为空， 如果配置了，就会只找这台tag，导致负载均衡不成功


分别打开端口为20000和20001服务器配置文件app/config/properties/service.php配置如下：

```php
return [
    'user' => [
        'name'        => 'user',
        'uri'         => [
            '192.168.7.197:20000',
            '192.168.7.197:20001',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'maxWaitTime' => 3,
        'maxIdleTime' => 60,
        'timeout'     => 8,
        'useProvider' => true,
        'balancer' => 'random',
        'provider' => 'consul',
    ]
```

- name 连接池的名字， 这里配置为user
- uri 服务器ip和端口，可以配置多个实现负载均衡，走配置负载均衡
- useProvider 开启consul负载均衡，这个参数要配置为true，才会走consul，这个参数为true后uri失效，服务器列表会走consul拉取
- balancer 制定负载均衡算法，swoft只实现了random算法，可以自己扩展实现算法





