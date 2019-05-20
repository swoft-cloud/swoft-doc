# 控制器 Controller

控制器作为HTTP服务的核心组件，串接起一次请求的整个生命周期. 通过 注解 的方式，相较于传统的 Controller，代码更简洁，用户可以更关注业务逻辑。

## 创建控制器

主要通过 `@Controller` 注解实现。代码可以放置任意位置，不过为了统一标准，建议放在 `app/Http/Controller` 下

## 注解

```php
/**
 * @Controller()
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

`@Controller`，类注解设置在Controller类上，标记当前类是一个Http控制器类

- prefix：指定路由前缀
- 显式指定路由前缀: `@Controller(prefix="/index")` 或 `@Controller("/index")`
- 隐式指定路由前缀: `@Controller()` 默认自动解析 controller class 的名称，并且使用驼峰格式。

隐式指定路由前缀访问URL：`http://127.0.0.1:18306/index/index`

显示指定路由前缀访问URL：`http://127.0.0.1:18306/v1/index/index`

控制器的URL规则通过 `@Controller` + `@RequestMapping` 注解实现，通常前者定义前缀，后者定义后缀

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

> @Controller 和 @RequestMapping 的搭配使用

## 控制器定义访问

关于 `@RequestMapping` 注解详细参考：[路由](route.md)

## 注意

在Swoft里不要按照传统的fpm框架继承父类控制器的成员属性在其他控制器使用，如：

```php
/**
 * @Controller()
 */
class BaseController
{
    protected $num;
}
/**
 * @Controller()
 * @Controller(prefix="/v1/index")
 */
class IndexController extends BaseController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index(){
        $this->num++;
        echo $this->num."\n";
    }
}
```

以上做法是错误的