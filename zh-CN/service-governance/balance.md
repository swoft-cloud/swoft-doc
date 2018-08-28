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
