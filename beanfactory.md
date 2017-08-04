# Bean配置

Bean配置规则

* 必须定义一个数组KEY且为字符串，KEY及是Bean名称，可以通过名称使用
* 数组值里面必须有KEY为class,class指定需要注入的类名称
* 其余数组配置项，若KEY为整数且值是整数，则是类构造函数配置，反之是类属性配置，KEY及是类成员名称
* base.php配置顺序没有强力要求，建议按使用顺序配置

Bean引用规则

* 引用properties.php分递归引用和直接引用两种方式
* ${beanName}这种方式引用Bean对象
* 构造函数和成员属性都可以使用以上引用规则

配置案例

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
            '${config.service.user.timeout}',
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

# Bean使用

有多种方式可以使用已经配置并注入的类\(Bean\),得到的结果和一个类的实例是完全一样使用，并且默认注入的类是单列的。常用有两种方式

* 通过名称从应用上下文获取
* 应用简写App中获取

```php
App::getBean("name");
ApplicationContext::getBean('name');
BeanFactory::getBean('name');
```

# BeanFactory

BeanFactory负责初始化\(base.php\)、创建、查询beans。bean其实就是类的对象，通常通过base.php，配置一个bean，bean之间可以相互注入，也可以引用properties.php属性配置。常用方法如下

1. 运行过程中创建一个Bean
2. 根据名称获取Bean
3. Bean是否存在

```php
class BeanFactory implements BeanFactoryInterface
{

    /**
     * 注入一个bean
     *
     * @param string       $beanName   名称
     * @param array|string $beanConfig 配置属性
     *
     * @return bool
     */
    public static function createBean(string $beanName, $beanConfig)
    {
        // ...
    }



    /**
     * 查询Bean
     *
     * @param  string $name 名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        // ...
    }

    /**
     * Bean是否存在容器中
     *
     * @param  string $name 名称
     *
     * @return bool
     */
    public static function hasBean($name)
    {
        // ...
    }

}
```



