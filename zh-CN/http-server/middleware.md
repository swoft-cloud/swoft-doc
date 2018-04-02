# HTTP中间件 Middleware

通过中间件, 可以实现在请求到达最终动作之前或之后, 对请求进行过滤和处理. 使用中间件的常见场景: 权限验证/参数验证/接口限制.

中间件可以很好的将部分逻辑从业务中分离.

## 定义

中间件的定义, 可以参考 `app/Middlewares/` 目录下的文件, 只需要实现 `Swoft\Http\Message\Middleware\MiddlewareInterface` 接口的 `process()` 方法, 就可以轻松实现一个中间件, 比如 `app/Middlewares/ActionTestMiddleware`:

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
 * @uses      ActionTestMiddleware
 * @version   2017年11月16日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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

通过 `@Middleware @Middlewares`, 可以很方便的使用中间件

中间件作用域: 当前 Action / 当前 Controller / 全局

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

`@Middlewares` 注解定义一组 `@Middleware`, 按照定义顺序依次执行, 使用参考 `app/Controllers/MiddlewareController.php`:

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
        $response = $handler->handle($request);
    
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
```
