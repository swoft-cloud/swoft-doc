# 中间件 Middleware

中间件是用于控制 **请求到达** 和 **响应请求** 的整个流程的，通常用于对请求进行过滤验证处理，当你需要对请求或响应作出对应的修改或处理，或想调整请求处理的流程时均可以使用中间件来实现。
中间件

## 定义中间件

只需要实现了 `Swoft\Http\Message\Middleware\MiddlewareInterface` 接口均为一个合法的中间件，其中 `process()` 方法为该中间件逻辑处理方法, 可以参考 `Swoft` 项目呢 `app/Middlewares/` 目录下的文件, 比如 `app/Middlewares/ActionTestMiddleware`:

```php
<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;


/**
 * @Bean()
 */
class ActionTestMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withAddedHeader('Middleware-Action-Test', 'success');
    }
}
```

## 使用

### 配置全局中间件

当你的自定义中间件需要全局请求应用，则可以考虑将此中间件作为全局中间件去使用，只需在 Bean 配置文件内配置 `serverDispatcher` 的 `middlewares` 属性，在数组中加入你的自定义中间件的命名空间地址，相关配置通常在 `app/config/beans/base.php` 内

```php
// 全局中间件配置: app/config/beans/base.php
return [
    ...
    'serverDispatcher' => [
        'middlewares' => [
            \Swoft\View\Middleware\ViewMiddleware::class,
            \Swoft\Session\Middleware\SessionMiddleware::class,
        ]
    ],
    ...
];
```

### 通过注解使用

通过 `@Middleware` 和 `@Middlewares`, 可以很方便的配置中间件到当前的 `Controller` 和 `Action` 内

- 当将此注解应用于 `Controller` 上，则作用域为整个 `Controller`
- 将此注解应用于 `Action` 上，则作用域仅为当前的 `Action`
- `@Middleware` 用于配置单个中间件
- `@Middlewares` 显而易见的是用于配置一组 `@Middleware`，按照定义顺序依次执行, 使用参考 `app/Controllers/MiddlewareController.php`

```php
<?php

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use App\Middlewares\GroupTestMiddleware;
use App\Middlewares\ActionTestMiddleware;
use App\Middlewares\SubMiddleware;
use App\Middlewares\ControlerSubMiddleware;
use App\Middlewares\ControlerTestMiddleware;


/**
 * @Controller("middleware")
 * @Middleware(class=ControlerTestMiddleware::class)
 * @Middlewares({
 *     @Middleware(ControlerSubMiddleware::class)
 * })
 */
class MiddlewareController
{
    /**
     * @RequestMapping()
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     * @Middleware(SubMiddleware::class)
     */
    public function action1(): array
    {
        return ['middleware'];
    }

    /**
     * @RequestMapping()
     * @Middleware(SubMiddleware::class)
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     */
    public function action2(): array
    {
        return ['middleware2'];
    }

    /**
     * @RequestMapping()
     */
    public function action3(): array
    {
        return ['middleware3'];
    }
}
```

### 中间件中断返回

当在实现验证检查类的中间件时，经常需要中断当前请求并直接给出响应，以下是中断流程的几种方式

#### 构造一个新的 Response 对象直接返回

```php
public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{
    $auth = false;
    // 如果验证不通过
    if (!$auth) {
        // response() 函数可以快速从 RequestContext 获得 Response 对象
        return response()->withStatus(401);
    }
    // 委托给下一个中间件处理
    $response = $handler->handle($request);
    return $response;
}
```

#### 抛出异常返回

只要在请求生命周期内抛出的异常会被 `ErrorHandler` 捕获并处理，中间件内抛出也是如此，这部分不属于中间件的内容，顾在此不多做阐述。

### 示例：提前拦截请求

> 注意： 拦截要在 `$handler->handle($request)` 之前

```php
<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * @Bean()
 */
class SomeMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if ($path === '/favicon.ico') {
            return \response()->withStatus(404);
        }

        return $handler->handle($request);
    }
```

### 示例：允许跨域

```php
<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * @Bean()
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('OPTIONS' === $request->getMethod()) {
            return $this->configResponse(\response());
        }

        $response = $handler->handle($request);

        return $this->configResponse($response);
    }

    private function configResponse(ResponseInterface $response)
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
}
```


