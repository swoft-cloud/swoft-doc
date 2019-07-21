# Bean

`Swoft` 中一个 `Bean` 就是一个类的一个对象实例。 容器就是一个巨大的工厂，用于存放和管理 `Bean 生命周期`。

## 创建 Bean

创建 Bean 
 - 直接定义注解 [@Bean](#@Bean())  的方式
 - 通过在`bean.php`中配置, 
 - 在`AutoLoader.php` 中定义

### @Bean()
 
```php
<?php declare(strict_types=1);

namespace SwoftTest\Bean\Testing\Definition;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class PrototypeClass
 *
 * @since 2.0
 *
 * @Bean(name="prototype", scope=Bean::PROTOTYPE, alias="pro")
 */
class PrototypeClass
{
    // ....
}
```

 - scope
    - Bean::SINGLETON [单例Bean](./singleton.md)
    - Bean::PROTOTYPE [原型Bean](./prototype.md) 
    - Bean::REQUEST  [请求Bean](./request.md)
    
 - name
    为 bean 指定一个名称，有时候你可能只需要配置一个 name 可以这样写`@Bean("xxx")`这样默认是参数的 name。
 
 - alias
    为 bean 指定一个别名, 你可以通过别名获取它
    
> `@Bean` 定义的在初始化的时候如果有`构造函数`需要注入参数 请给`默认值`，然后通过[配置](#Config)的覆盖构造函数所需参数    

> scope 注入 Bean 类型，默认单例 

### 在 bean.php配置文件中,创建 Bean

例如下面这个例子,下面我要利用配置文件将它 加入到 Bean 中

```php
<?php declare(strict_types=1);

namespace SwoftTest\Bean\Testing\Definition;

/**
 * TestBean
 *
 * @since 2.0
 */
class TestBean
{

    /**
     * @var SingletonClass
     */
    private $singleton;

     /**
     * @var \JWT
     */
    private $jwt;

    /**
     * TestBean constructor.
     *
     * @param SingletonClass $singleton
     */
    public function __construct(SingletonClass $singleton = null)
    {
        $this->singleton = $singleton;
    }
}
```

配置方式 指定 class 的方式

```php
 'testBean'     => [
     'class'    => TestBean::class,
     [\bean('singleton')],
     'jwt'      => \bean('jwt'),
     '__option' => [
         'scope' => Bean::PROTOTYPE,
         'alias' => 'testBean-alias'
     ]
 ],
```

`testBean` 这个 key 你可以理解是一个`Bean`的名称。

- **class** 参数的指定 `Bean` 使用哪一个类
- 示例中的数组` [\bean('singleton')],` 是 `TestBean`类构造函数所需参数,不推荐使用构造注入，请使用`@Inject`
- **__option** 这个`swoft` 自带的你可以指定：
    - **scope** 指定 `Bean` 是用哪个级别,如果没有默认`单例模式`
    - **alias** 指定 `Bean` 的别名 

> Swoft 的 `类`构造方法, 不会自动依赖注入

当然你也可以注入`自己定义的属性` 比如：

- **jwt** 这个是上面`TestBean`类定义的属性，底层会通过`反射`注入`config`中的参数

> 通过 配置文件配置的 `Bean` 优先级`最高`因为它是最后执行的，如果配置的已经是一个 `Bean` ，`config`的 配置的将会覆盖它

### 在`AutoLoader.php` 中定义

```php
<?php declare(strict_types=1);

namespace App;

use Swoft\Db\Database;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * 使用 beans 方法 和在 bean.php 中配置的一样的使用方式,它的优先级在bean.php定义的 bean 之下
     *    
     * @return array
     */
    public function beans(): array
    {
        return [
            'db' => [
                'class'    => Database::class,
                'dsn'      => 'mysql:dbname=test;host=172.17.0.4',
                'username' => 'root',
                'password' => 'swoft123456',
            ],
        ];
    }
}
```

## Bean 初始化

每一个 `Bean`初始化的时候会自动检查`init()`这个方法是否存在

会在`Bean`初始化完之后调用，你可以在该方法中进行一些初始化操作， 作用类似 `golang` 中的`init`方法。

## 获取 Bean 
 
### @Inject

`score`为 `Bean::SINGLETON` 级别的 `bean` 可以通过 `@Inject` 属性注入，底层会通过反射自动注入属性中。 

> `Bean::PROTOTYPE`, `Bean::REQUEST`  bean不能使用 `@Inject` 注入

```php
/**
 * @Inject("config")
 *
 * @var Config
 */
private $config;
```

- **name** 定义属性注入的bean名称。如果`name`为空，默认为`@var` 定义的类型。
 这个`name` 可以是一个完整的类名，也可以是`bean别名/bean名称`。
 

 <p class="tip"> 如果 @Inject 没有指定 Bean 名称, 会自动根据 @var 的类型查找 Bean </p>

> tips： 如果要使用 `@Inject` 属性注入必须有 `类注解`才可以，不然不会被解析。

### BeanFactory

`BeanFactory`提供了一种先进的配置机制来管理任何种类的bean。

获取`score`为`Bean::SINGLETON`，`Bean::PROTOTYPE`

可以使用下面，以获取 `wsRouter` 为例：

 ```php
/** @var Router $router */
$router = Swoft::getBean('wsRouter');
$router = BeanFactory::getBean('wsRouter');
$router = Container::$instance->get('wsRouter')
$router = BeanFactory::getContainer()->get('wsRouter')
```

上述这几种方式都可以获取`Bean::SINGLETON`或`Bean::PROTOTYPE`类型的`bean`

你也可以指定类型获取 ：

例如：获取单例类型的`bean`

```php
/* @var WsDispatcher $dispatcher */
$dispatcher = BeanFactory::getSingleton('wsDispatcher');
```

 > 注意`BeanFactory::getBean`只能获取到 框架启动加载的 bean，不能获取到 `scope` 为 `request` 的`bean`。
 
[`request bean`](./request.md) 的类型是 2.x 新加的
 
获取 `score`为 `request`类型的 你可以这样获取

```php
$requestBean = BeanFactory::getRequestBean(RequestClass::class, (string)Co::tid());
```

一般 `request` 类型的 `bean` 通常是与`顶级协程ID`绑定在一起的

判断`当前环境`是否存在 某个 `bean`

```php
$exist = BeanFactory::hasBean($name);
```

判断是否是一个单例的 `bean`

```php
$isSingleton = BeanFactory::isSingleton('name')
```
