# 路由

Swoft与传统的PHP框架不一样，并没有采用配置文件的方式来配置路由，而采用了注解。在Swoft里我们可以使用 `@RequestMapping` 注解快速的添加路由。

## 路由器配置

```php
// at file: vendor/swoft/http-server/src/AutoLoader.php
    'httpRouter'      => [
        'name'            => 'swoft-http-router',
        // config
        'ignoreLastSlash' => true,
        'tmpCacheNumber'  => 500,
        // 'handleMethodNotAllowed' => false
    ],
```

### 路由配置说明

- `ignoreLastSlash` _bool_ 默认：`true` 是否忽略URI path最后的 `/`

如果设置为 `false` 不忽略， `/home` 与 `/home/` 将是两个不同的路由

- `tmpCacheNumber` _int_ 默认：`500` 动态路由缓存数量。

动态参数路由匹配后会缓存下来，下次相同的路由将会更快的匹配命中。

- `handleMethodNotAllowed` _bool_ 默认：`false` 是否处理 MethodNotAllowed

为了加快匹配速度，默认method不匹配也是直接抛出 Route not found 错误。如有特殊需要可以开启此选项，开启后将会抛出 Method Not Allowed 错误

## RequestMapping 注解

- route：路由规则path
- method：请求方式（GET、POST、PUT、PATCH、DELETE、OPTIONS、HEAD）

> 注意：每个方法上尽量只写一个 `@RequestMapping` 注解，以免出现紊乱。

### route参数

使用方法在控制器方法中加入 `RequestMapping` 注解

- 显示指定路由后缀：`@RequestMapping("index")` 或 `@RequestMapping(route="index")`
- 隐式指定路由后缀: 使用 `@RequestMapping()` 默认解析方法名为后缀

```php
/**
 * @Controller()
 */
class UserController
{
    /**
     * @RequestMapping()
     */
    public function index()
    {}

    /**
     * @RequestMapping("index")
     */
    public function index()
    {}

    /**
     * @RequestMapping(route="index")
     */
    public function index()
    {}
}
```

上面的效果一样，为index方法绑定的路由为 `/user/index`，允许的请求方法为默认的 `GET` 和 `POST`。

绑定路由path参数：

- 指定路由参数: `@RequestMapping(route="index/{name}")`，Action 方法中可以直接使用 `$name` 作为方法参数
- 当路由参数被 `[]` 包起来则URL path传递参数是可选的，可有可无

```php
/**
 * @Controller()
 */
class UserController
{
    /**
     * @RequestMapping(route="/index/{name}")
     */
    public function index(string $name)
    {}

    /**
     * @RequestMapping(route="/index/{name}")
     */
    public function index(string $name)
    {}
}
```

### method参数

决定被请求控制器的操作允许哪种请求方式，使用方法在控制器中的 `@RequestMapping` 注解配置method参数，可以是 GET、POST、PUT、PATCH、DELETE、OPTIONS、HEAD 中的一个或多个。

```php
/**
 * @Controller()
 */
class UserController
{
    /**
     * @RequestMapping(method=RequestMethod::GET)
     */
    public function index()
    {}

    /**
     * @RequestMapping(method={RequestMethod::GET,RequestMethod::POST})
     */
    public function index()
    {}

    /**
     * @RequestMapping("index", method=RequestMethod::PUT)
     */
    public function index()
    {}
}
```

- 限定HTTP方法: `@RequestMapping(method={RequestMethod::GET})` 指定路由支持的HTTP方法，默认是支持 `GET` 和 `POST`
  - 比如 `method={RequestMethod::POST,RequestMethod::PUT}` 设置路由支持 `POST` 和 `PUT`

## 使用注意

- **通常情况**,一个完整的路由path等于 Controller的 `prefix` + Action的 `route`
- **特殊的**，当你的action上的路由以 `/` 开头时，那完整的路由就是它，即不会再将 prefix 添加到它的前面。

> 请切记要引入相关的注解类
`Swoft\Http\Server\Annotation\Mapping\RequestMapping`
`Swoft\Http\Server\Annotation\Mapping\RequestMethod`

## 获取匹配结果

```php
[$status, $path, $route] = $request->getAttribute(Request::ROUTER_ATTRIBUTE);
```

