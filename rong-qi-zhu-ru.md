# 全局容器

每一个被swoft管理的PHP对象称之为Bean，swoft提供了一个IoC容器来初始化对象和获取对象，解决对象间的依赖管理。

## 注入对象

目前支持两种方式注入对象，数组配置和@Bean注解。

#### 数组配置

配置规则

* 必须定义一个数组KEY且为字符串，KEY及是Bean名称，可以通过名称使用
* 数组值里面必须有KEY为class,class指定需要注入的类名称
* 其余数组配置项，若KEY=0且值为自然数组\(数组个数对应构造函数参数\)，则是类构造函数配置，反之是类属性配置，KEY及是类成员名称，属性配置只要不是静态属性都可以。
* base.php配置顺序没有强力要求，建议按使用顺序配置，方便阅读

```php
// properties.php
return [
    'config.service.user.maxActive' => 10,
    'service' =>[
        'user' => [
            'timeout' => 21200
        ]
    ]
];

// base.php
return [
    'config'         => [
        'properties' => require_once __DIR__ . '/' . APP_ENV . '/properties.php',
    ],
    'userProvider' => [
        'class' => \swoft\service\ConsulProvider::class
    ],
    "userPool"  => [
        "class"           => \swoft\pool\ServicePool::class, // 
        [
            '${randomBalancer}', // 构造函数参数，通过引用值使用
            [
                'timeout'=>'${config.service.user.timeout}'
            ],
            'refVal',
        ],
        "uri"             => '127.0.0.1:8099,127.0.0.1:8099', 
        "maxIdel"         => 6,
        "maxActive"       => '${config.service.user.maxActive}', // 直接引用方式使用
        "timeout"         => '${config.service.user.timeout}', // 递归引用使用
        "balancer"        => '${randomBalancer}', //引用已经注入的Bean对象 
        "serviceName"     => 'user',
        "useProvider"     => false,
        'serviceprovider' => '${userProvider}'
    ],
];
```

#### 注解配置

注解配置更加简洁，通过在类和属性上使用@Bean和@Inject注解。

```php
/**
 *
 * 注解注入demo
 *
 * @Bean("userModel")
 * @uses      UserModel
 * @version   2017年08月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserModel
{
    /**
     * @Inject("${logger}")
     * @var Logger
     */
    private $d2;

    /**
     * @Inject()
     * @var IndexLogic
     */
    private $data;

    /**
     * @Inject("${config.service.user.timeout}")
     * @var int
     */
    private $data2;

    /**
     * @Inject(name="${config.user.stelin.steln}")
     * @var string
     */
    private $data3;

    private $data4;

    private $data5;


    public function getData()
    {
        return $this->data;
    }
}
```



