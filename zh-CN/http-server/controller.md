# 控制器 Controller

控制器作为HTTP服务的核心组件，串接起一次请求的整个生命周期. 通过 注解 的方式，相较于传统的 Controller，代码更简洁，用户可以更关注业务逻辑。

## 创建控制器

主要通过 `@Controller` 注解实现。代码可以放置任意位置，不过为了统一标准，建议放在 `app/Http/Controller` 下

## 注解

### Controller

Http 控制器类注解tag `@Controller`

- 注解类： `Swoft\Http\Server\Annotation\Mapping\Controller`
- 作用范围： `CLASS`
- 拥有属性：
  - `prefix` 指定路由前缀

> 通常仅有 `@Controller` 是没有什么效果的，它需要配合接下来的 `@RequestMapping` 一起才能正确的工作。

## 使用

- 显式指定路由前缀: `@Controller(prefix="/index")` 或 `@Controller("/index")`
- 隐式指定路由前缀: `@Controller()` 默认自动解析 controller class 的名称，并且使用小驼峰格式。

```php
<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * @Controller()
 */
class IndexController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index(){}
}

// OR

/**
 * @Controller(prefix="/v1/index")
 */
class IndexController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index(){}
}
```

启动服务器：

- 隐式指定路由前缀访问URL：`http://127.0.0.1:18306/index/index`
- 显示指定路由前缀访问URL：`http://127.0.0.1:18306/v1/index/index`

## 说明

一个完整的路由规则是通过 `@Controller` + `@RequestMapping` 注解实现，通常前者定义前缀，后者定义后缀。

- 如下，访问路由是 `/v1/users/list` (`/v1/users` + `list`)

```php
/**
 * @Controller(prefix="/v1/users")
 */
class UsersController
{
    /**
     * @RequestMapping(route="list")
     */
    public function list(){}
}
```

访问URL：`http://127.0.0.1:18306/v1/users/list`

当 `@Controller` 为空时

```php
/**
 * @Controller()
 */
class UsersController
{
    /**
     * @RequestMapping(route="list")
     */
    public function list(){}
}
```

访问URL：`http://127.0.0.1:18306/users/list`

> @Controller 和 @RequestMapping 的搭配使用。关于 `@RequestMapping` 注解详细参考：[路由](route.md)

## 注意

在Swoft里不要按照传统的fpm框架继承父类控制器的成员属性在其他控制器使用，这种做法是错误的。

如：

```php
/**
 * @Controller()
 */
class BaseController
{
    protected $num;
}

/**
 * @Controller(prefix="/v1/index")
 */
class IndexController extends BaseController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index()
    {
        $this->num++;
        echo $this->num."\n";
    }
}
```

