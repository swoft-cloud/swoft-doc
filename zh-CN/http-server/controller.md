# 控制器 Controller

控制器作为HTTP服务的核心组件, 串接起一次请求的整个生命周期. 通过 **注解** 的方式, 相较于传统的 controller, 代码更简洁, 用户可以更关注业务逻辑.

## 路由 Route

主要通过 `@Controller` + `@RequestMapping` 注解实现, 前者定义 **前缀**, 后者定义 **后缀**

### `@Controller`

设置在 Controller 类上

- 显式指定路由前缀: `@Controller(prefix="/route")` 或 `@Controller("/route")`
- 隐式指定路由前缀: `@Controller()` 默认自动解析 controller 前的名称, 并且使用驼峰格式.
  - 比如 `HttpClientController` 将会设置路由 prefix 为 `httpClient`

### `@RequestMapping`

注解参数：

- `route` 设置路由path，也是默认参数。
- `method` 设置允许的请求方法, 可以多个。eg `GET` `POST`

设置控制器类的在Action方法上

```php
/**
 * @RequestMapping(route="index")
 * @RequestMapping(route="index", method=RequestMethod::GET)
 * @RequestMapping(route="index", method={RequestMethod::POST,RequestMethod::PUT})
 */
```

- 显式指定路由后缀: `@RequestMapping(route="index")`或 `@RequestMapping("index")`
- 隐式指定路由后缀: 不使用 `@RequestMapping` 或者使用 `@RequestMapping()`, 默认解析方法名为后缀
- 限定HTTP方法: `@RequestMapping(route="index", method=RequestMethod::GET)` 指定路由支持的HTTP方法, 默认是支持`GET`和`POST`。
  - 比如 `method={RequestMethod::POST,RequestMethod::PUT}` 设置路由支持 `POST` 和 `PUT`
- 指定路由参数: `@RequestMapping(route="anyName/{name}")`, Action 方法中可以直接使用 `$name` 作为方法参数

## 使用说明

- 通常一个完整的路由path等于 Controller的`prefix` **+** Action的`route`
- 当你的action上的路由以 `/` 开头时，那完整的路由就是它，即不会再将 `prefix` 添加到它的前面。
- 请切记要引入相关的注解tag class

```php
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
```

## 快速创建控制器

可以通过命令行命令快速创建控制器类，以方便快速开发使用。

```bash
// Gen DemoController class to `@app/Controllers`
php bin/swoft gen:controller demo --prefix /demo -y

// Gen UserController class to `@app/Controllers`(RESTFul type)
php bin/swoft gen:controller user --prefix /users --rest
```

## 示例

常用方法可以参考 `app/Controllers/RouteController.php`:

```php
/**
 * action demo
 *
 * @Controller(prefix="/route")
 */
class RouteController
{
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
     * @param bool    $bool
     * @param Request  $request
     * @param int      $bid
     * @param string   $name
     * @param int      $uid
     * @param Response $response
     *
     * @return array
     */
    public function funcArgs(bool $bool, Request $request, int $bid, string $name, int $uid, Response $response)
    {
        return [$bid, $uid, $bool, $name, \get_class($request), \get_class($response)];
    }
    ...
}
```

## 请求 Request

Swoft HTTP服务中的 Request, 是对 [`\Swoole\Http\Request`](https://wiki.swoole.com/wiki/page/328.html) 基于 PSR 标准的封装, 常用方法可以参考 `app/Controllers/DemoController.php`:

```php
 public function index(Request $request)
{
    // 获取所有GET参数
    $get = $request->query();
    // 获取name参数默认值defaultName
    $getName = $request->query('name', 'defaultName');
    // 获取所有POST参数
    $post = $request->post();
    // 获取name参数默认值defaultName
    $postName = $request->post('name', 'defaultName');
    // 获取所有参，包括GET或POST
    $inputs = $request->input();
    // 获取name参数默认值defaultName
    $inputName = $request->input('name', 'defaultName');

    return compact('get', 'getName', 'post', 'postName', 'inputs', 'inputName');
}
```

**注意**: [`\Swoole\Http\Request`](https://wiki.swoole.com/wiki/page/328.html) 对 HTTP Request 进行了封装, 不能像以往一样使用 `$_POST / $_GET` 等全局变量. 也不推荐这样的使用方式, 框架层通常都做了更好的封装和兼容, 比如 `$_POST` 无法取到 `application/json` 格式的数据

## 响应 Response

Swoft 对HTTP服务的 Response 做了很好的封装, 其中一个设计哲学:

> 返回的格式类型, 不应该由服务端指定, 而是根据客户端请求时的 Header 里面的 Accept 决定

当 Action 返回一个 array 或 Arrayable 对象, Response 将根据 Request Header 的 Accept 来返回数据, 目前支持 `View / Json / Raw`

可以参考 `app/Controllers/IndexController.php`:

```php
/**
 * @RequestMapping("/")
 * @View(template="index/index")
 * @return array
 */
public function index(): array
{
    $name = 'Swoft';
    $notes = [
        'New Generation of PHP Framework',
        'Hign Performance, Coroutine and Full Stack'
    ];
    $links = [
        [
            'name' => 'Home',
            'link' => 'http://www.swoft.org',
        ],
        [
            'name' => 'Documentation',
            'link' => 'http://doc.swoft.org',
        ],
        [
            'name' => 'Issue',
            'link' => 'https://github.com/swoft-cloud/swoft/issues',
        ],
        [
            'name' => 'GitHub',
            'link' => 'https://github.com/swoft-cloud/swoft',
        ],
    ];
    // 返回一个 array 或 Arrayable 对象，Response 将根据 Request Header 的 Accept 来返回数据，目前支持 View, Json, Raw
    return compact('name', 'notes', 'links');
}
```

### 支持返回的数据类型

- 基本数据类型: `bool` `int` `float(double)` `string`
- `array`
- `\Swoft\Contract\Arrayable` 对象
- `XxxException`: 在 Controller 内抛出异常将由 ExceptionHandler 捕获并进行处理, `4xx/5xx` 的状态码也是通过抛异常, 然后由 ExceptionHandler 捕获并统一进行处理

### 使用视图

可以通过 `@View` 注解 或 `view()` 帮助函数来使用视图, 可以参考 `app/Controllers/IndexController.php`

## 最佳实践

- 使用 PSR 标准来封装 HTTP服务的 Request 和 Response
- **约定大于配置**, 路由应该在用户看到 URI 的时候, 就能找到相应的 `Controller/Action`

## 其他

Controller 中也可以使用 Bean 相关的方法

> **注意**: `@Controller` 注解已经实现了 `@Bean` 的功能, 不能和 `@Bean` 注解同时使用

其他注解方法, 比如 `@Inject`, 参考 [Bean容器](../core/container.md)
