todo: 迁移到 beta 版

# 控制器

控制器提供注解和手动注入两种方式，建议更多使用注解，无需手动维护路由表；注解注册控制器很简单，仅仅使用 `@Controller` 注解，就可以定义一个最简单的控制器，无需任何父类继承、路由注册。

## 控制器常用注解

### @Controller

- `@Controller`注解，其中一部分功能和 `@Bean` 完全一样
- 使用了 `@Controller`后不能再使用 `@Bean` 注解。
- `@Controller` 注解不需要指定bean名称，统一类为bean名称
- `@Controller()` 默认自动解析controller前缀，并且使用驼峰格式。
- `@Controller(prefix="/route")`或 `@Controller("/route")` 都是定义控制器, 并且指定了路由path前缀。

### @Inject

- @Inject注入一个Bean对象
- @Inject不仅可以注入配置Bean，还可以注入@Bean的对象
- @Inject默认注入按照类名称Bean
- `@Inject(name="MyBean")` 或 `@Inject("MyBean")`，注入名称为`MyBean`的Bean

### @RequestMapping

- `@RequestMapping(route="index")`或 `@RequestMapping("index")` 含义是一样的，都是添加路由后缀 `index`。
    - 结合之前在controller类上加的路由前缀，完整的路由即是 `/route/index`
- `@RequestMapping(route="index", method=RequestMethod::GET)` 指定路由支持的HTTP方法，默认是支持`GET`和`POST`
- 不使用`@RequestMapping`注解，默认解析方法名称为action


```php
/**
 * action demo
 *
 * @Controller(prefix="/route")
 *
 * @uses      TestController
 * @version   2017年11月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RouteController
{
    /**
     * @Inject()
     * @var IndexLogic
     */
    private $logic;
    /**
     * @RequestMapping()
     */
    public function index()
    {
        return 'index';
    }

    /**
     * @RequestMapping(route="user/{uid}/book/{bid}/{bool}/{name}")
     *
     * @param bool                $bool
     * @param \Swoft\Web\Request  $request
     * @param int                 $bid
     * @param string              $name
     * @param int                 $uid
     * @param \Swoft\Web\Response $response
     *
     * @return array
     */
    public function actionFuncArgs(bool $bool, Request $request, int $bid, string $name, int $uid, Response $response)
    {
        return [$bid, $uid, $bool, $name, get_class($request), get_class($response)];
    }

    /**
     * @RequestMapping(route="hasNotArg")
     *
     * @return string
     */
    public function actionHasNotArgs()
    {
        return 'hasNotArg';
    }

    /**
     * @RequestMapping(route="hasAnyArgs/{bid}")
     * @param \Swoft\Web\Request $request
     * @param int                $bid
     *
     * @return string
     */
    public function actionHasAnyArgs(Request $request, int $bid)
    {
        return [get_class($request), $bid];
    }

    /**
     * @RequestMapping(route="hasMoreArgs")
     *
     * @param \Swoft\Web\Request $request
     * @param int                $bid
     *
     * @return array
     */
    public function actionHasMoreArgs(Request $request, int $bid)
    {
        return [get_class($request), $bid];
    }

    /**
     * optional parameter
     *
     * @RequestMapping(route="opntion[/{name}]")
     *
     * @param string $name
     * @return array
     */
    public function actionOptionalParameter(string $name)
    {
        return[$name];
    }

    /**
     * optional parameter
     *
     * @RequestMapping(route="anyName/{name}")
     *
     * @param string $name
     * @return array
     */
    public function funcAnyName(string $name)
    {
        return [$name];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionNotAnnotation(Request $request)
    {
        return [get_class($request)];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function onlyFunc(Request $request)
    {
        return [get_class($request)];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function BehindAction(Request $request)
    {
        return [get_class($request)];
    }
}
```

## Controller 返回的数据类型

我们不建议 Action 指定返回的格式类型，而是根据客户端请求时的 Header 里面的 Accept 决定，比如 Accept 为 `application/json`，我们则应该返回 Json 格式，为 `text/html` 则应该返回 View 视图，为 `text/plain` 则应该返回一个 raw body 。不用担心这部分，我们已经为您实现了，您只需要在 Action 内返回以下类型即可。

- 数组
- 字符串
- 布尔类型
- 数字 int, float ...
- 实现 `\Swoft\Contract\Arrayable` 的对象

目前我们仅支持了 `View`, `Json`, `Raw` 三种格式，后续我们会增加更多的格式。

在 Controller 内抛出异常将由 ExceptionHandler 捕获并进行处理，包括我们建议返回给客户端一个 4xx, 5xx 的状态码时，也是抛出一个相对应的异常，然后由 ExceptionHandler 捕获并统一进行处理
