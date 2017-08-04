# Bean工程

BeanFactory负责初始化\(base.php\)、创建、查询beans。bean其实就是类的对象，通常通过base.php，配置一个bean，bean之间可以相互注入，也可以引用properties.php属性配置。

Bean配置规则
- 必须定义一个数组KEY且为字符串，KEY及是Bean名称，可以通过名称使用
- 数组值里面必须有KEY为class,class指定需要注入的类名称



```
return [
    'config'         => [
        'properties' => require_once __DIR__ . '/' . APP_ENV . '/properties.php',
    ],
    "userPool"  => [
        "class"       => \swoft\pool\ServicePool::class,
        "uri"         => '127.0.0.1:8099,127.0.0.1:8099',
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => '${config.service.user.timeout}',
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false,
        'serviceprovider' => '${userProvider}'
    ],
];
```




