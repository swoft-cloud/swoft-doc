# Bean使用

有多种方式可以使用已经配置并注入的类\(Bean\),得到的结果和一个类的实例是完全一样使用，并且默认注入的类是单例的。常用有两种方式

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
// 类名创建
BeanFactory::createBean("myBean", MyBean.class);

// 配置创建
$beanConfig = [
    'class' => MyBean.class,
    'pro1' => 'v1',
    'pro2' => 'v2',
    [ // 构造函数参数
        'arg1',
        '${beanName}'
    ]
];
BeanFactory::createBean('myBean', $beanConfig);

// 获取一个Bean,没有初始化，从配置中创建
BeanFactory::getBean("name");

// 某个Bean是否已创建
BeanFactory::hasBean("name");
```



