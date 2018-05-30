# 容器
Swoft 中一个 Bean 就是一个类的一个对象实例。 容器就是一个巨大的工厂，用于存放和管理 Bean 生命周期。

## 注解

### @Bean

> 命名空间：`\Swoft\Bean\Annotation\Bean`

- name 定义 Bean 别名，缺省默认类名
- scope 注入 Bean 类型，默认单例，Scope::SINGLETON/Scope::PROTOTYPE(每次创建)
- ref 指定引用 Bean ，用于定义在接口上面，指定使用哪个接口实现。

### @Inject

> 命名空间：`\Swoft\Bean\Annotation\Inject`

- name 定义属性注入的bean名称，缺省属性自动类型名称

## 定义bean

bean有两种方式定义，注解和数组配置

### 数组定义

```php
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
```

- 数组中必须要有class字段定义
- pro1/pro1 和类面的成员变量名称是一一对应
- 属性值和构造函数参数值，都可以通过 ${xxx} 和 ${config.xx}, 注入Bean和引用properties配置信息

### 注解定义

注解定义使用PHP文档注解，在类上做一些标记，通过解析类注解，实现不同的功能。

```php
 /**
  * @\Swoft\Bean\Annotation\Bean("userData")
  */
 class XxxBean
 {

 }
```

## 操作Bean

```php
App::getBean("name");
ApplicationContext::getBean('name');
BeanFactory::getBean('name');
BeanFactory::hasBean("name");
```

- App/ApplicationContext/BeanFactory都可从容器中得到Bean
- hasBean 某个bean是否存在

## 实例

### 别名定义

```php
/**
 * @\Swoft\Bean\Annotation\Bean("userData")
 */
class UserData
{
    public function getData()
    {
        return [];
    }
}

/**
 * @\Swoft\Bean\Annotation\Bean()
 */
class UserLogic
{
    /**
     * @\Swoft\Bean\Annotation\Inject("userData")
     * @var \UserData
     */
    private $userData;

    private function getUser()
    {
        return $this->userData->getData();
    }
}

```

### 缺省定义

```php
/**
 * @\Swoft\Bean\Annotation\Bean("userData")
 */
class UserData
{
    public function getData()
    {
        return [];
    }
}

/**
 * @\Swoft\Bean\Annotation\Bean()
 */
class UserLogic
{
    /**
     * @\Swoft\Bean\Annotation\Inject()
     * @var \UserData
     */
    private $userData;

    private function getUser()
    {
        return $this->userData->getData();
    }
}
```

### 接口引用

- 接口上面指定了使用的实现bean别名
- 接口使用处，无需指定使用那个别名，会根据接口上面的引用注入不同的实例bean

 ```php
 /**
  * @\Swoft\Bean\Annotation\Bean(ref="boy")
  */
 interface UserInterface
 {
     public function getData();
 }

 /**
  * @\Swoft\Bean\Annotation\Bean("boy")
  */
 class UserBoy implements \UserInterface
 {
     public function getData()
     {
         return 'boy';
     }
 }

 /**
  * @\Swoft\Bean\Annotation\Bean("girl")
  */
 class UserGirl implements \UserInterface
 {
     public function getData()
     {
         return 'girl';
     }
 }

 /**
  * @\Swoft\Bean\Annotation\Bean()
  */
 class UserLogic
 {
     /**
      * @\Swoft\Bean\Annotation\Inject()
      * @var \UserInterface
      */
     private $userData;

     private function getUser()
     {
         return $this->userData->getData();
     }#
 }
 ```