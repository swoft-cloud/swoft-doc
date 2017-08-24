# 全局容器

每一个被swoft管理的PHP对象称之为Bean，swoft提供了一个IoC容器来初始化对象和获取对象，解决对象间的依赖管理。properties.php可以配置容器相关参数。

```php
return [
    ...
    'autoInitBean' => true,// 是否自动初始化bean,如果false，使用bean时创建Bean，默认是false
    'beanScan' => [ // 配置注解自动扫描的命令空间
        'app\controllers',
        'app\models',
        'app\beans',
    ],
    ...
];
```

## 注入对象与引用

目前支持两种方式注入对象，数组配置和@Bean注解。

配置规则

* 必须定义一个数组KEY且为字符串，KEY及是Bean名称，可以通过名称使用
* 数组值里面必须有KEY为class,class指定需要注入的类名称
* 其余数组配置项，若KEY=0且值为自然数组\(数组个数对应构造函数参数\)，则是类构造函数配置，反之是类属性配置，KEY及是类成员名称，属性配置只要不是静态属性都可以。
* base.php配置顺序没有强力要求，建议按使用顺序配置，方便阅读

引用规则

* 引用properties.php分递归引用和直接引用两种方式
* ${beanName}这种方式引用Bean对象
* 构造函数参数和成员属性都可以使用以上引用规则

#### 数组配置

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
 * 注解注入demo，如下注入一个名为userModel的bean
 *
 * @Bean("userModel")
 * ...
 */
class UserModel
{
    /**
     * 注入logger
     *
     * @Inject("${logger}")
     * @var Logger
     */
    private $property;

    /**
     * 注入logger
     *
     * @Inject(name="${logger}")
     * @var Logger
     */
    private $property2;

    /**
     * 注入IndexLogic.class，默认注入该属性，对应的类型名(包含命令空间)Bean
     *
     * @Inject()
     * @var IndexLogic
     */
    private $property3;

    /**
     * 注入properties.php值
     * 
     * @Inject("${config.service.user.timeout}")
     * @var int
     */
    private $property4;

    /**
     * 注入properties.php值
     *
     * @Inject(name="${config.user.stelin.steln}")
     * @var string
     */
    private $property5;

    /**
     * 未注入，使用默认值
     *
     * @var string
     */
    private $property6 = "default";


    public function getData()
    {
        return $this->data;
    }
}
```

> @Bean里面只能使用双引号
>
> @Bean\("userModel"\)或@Bean\(name="userModel"\)含义是一样。
>
> @Bean\(\)这种格式含义是注入的bean名称是使用类名\(包含命名空间\)。
>
> @Bean\(name="beanName",scope=Scope::SINGLETON\) 默认注入Bean都是单例，可以scope属性设置其类型
>
>
>
> @Inject使用格式和@Bean基本一样，注意通过注解目前不支持构造函数参数注入
>
> @Inject\("name="${logger}"\)或@Inject\("${logger}"\)注入名称为logger的Bean到属性
>
> @Inject\(name="${config.user.stelin.steln}"\)注入properties里面配置的值，可以层级和直接方式配置。
>
> @Inject\(\)默认注入该属性，对应的类型名\(包含命令空间\)Bean







