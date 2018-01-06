# Bean使用

有多种方式可以使用已经配置并注入的类\(Bean\),得到的结果和一个类的实例是完全一样使用，并且默认注入的类是单例的。常用有两种方式

* 通过名称从应用上下文获取
* 应用简写App中获取

```php
App::getBean("name");
ApplicationContext::getBean('name');
BeanFactory::getBean('name');
```

## BeanFactory

BeanFactory负责初始化\(base.php\)、创建、查询beans。bean其实就是类的对象，通常通过base.php，配置一个bean，bean之间可以相互注入，也可以引用properties.php属性配置。常用方法如下

1. 运行过程中创建一个Bean
2. 根据名称获取Bean
3. Bean是否存在

```php
// 类名创建
BeanFactory::createBean("myBean", MyBean::class);

// 配置创建
$beanConfig = [
    'class' => MyBean::class,
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

### 通过注解创建bean

将会自动的扫描解析并注册通过注解配置的bean。

- 示例： 通过注解注册一个事件监听器

```php
<?php
namespace App\Listeners;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class TestListener
 * @Listener("test.event")
 * @package App\Listeners
 */
class TestListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        var_dump('handle event: ' . $event->getName());
    }
}

```

## 注解扫描配置

配置文件在： `config/properties/app.php`

```php
    'beanScan'          => [
        'App\Controllers',
        'App\Models',
        'App\Middlewares',
        'App\Tasks',
        'App\Services',
        'App\Process',
        'App\Breaker',
        'App\Pool',
    ],
```

> 若你新建了自定义的目录，且需要框架扫描里面的注解信息。请在这里面配置对应的命名空间。
