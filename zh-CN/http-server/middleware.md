## 声明一个中间件

中间件是用于控制 请求到达 和 响应请求 的整个流程的，通常用于对请求进行过滤验证处理，当你需要对请求或响应作出对应的修改或处理，或想调整请求处理的流程时均可以使用中间件来实现。 

## 定义中间件

只需要实现了 Swoft\Http\Server\Contract\MiddlewareInterface 接口均为一个合法的中间件，其中 process() 方法为该中间件逻辑处理方法。

```php
namespace App\Http\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Contract\MiddlewareInterface;
/**
 * @Bean()
 */
class ControllerMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response;
    }
}
```
### 使用

### 配置全局中间件

当你的自定义中间件需要全局请求应用，则可以考虑将此中间件作为全局中间件去使用，只需在 Bean 配置文件内配置 httpDispatcher 的 middlewares 属性，在数组中加入你的自定义中间件的命名空间地址，相关配置通常在 app/bean.php 内

```php
return [
    ...
    'httpDispatcher'=>[
        'middlewares'=>[
            AuthMiddleware::class,
            ApiMiddleware::class
        ]
    ]
    ...
]
```

### 通过注解使用

通过 `@Middleware` 和 `@Middlewares`, 可以很方便的配置中间件到当前的 Controller 和 Action 内

当将此注解应用于 `Controller` 上，则作用域为整个 `Controller`
将此注解应用于 `Action` 上，则作用域仅为当前的 `Action`
`@Middleware` 用于配置单个中间件
`@Middlewares` 显而易见的是用于配置一组 @Middleware，按照定义顺序依次执行

```php
namespace App\Http\Controller;

use App\Http\Middleware\ApiMiddleware;
use App\Http\Middleware\IndexMiddleware;
use App\Http\Middleware\ControllerMiddleware;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * @Controller()
 * @Middlewares({
 *      @Middleware(ApiMiddleware::class),
 *      @Middleware(ControllerMiddleware::class)
 * })
 */
class MiddlewareController
{
    /**
     * @RequestMapping()
     * @Middleware(IndexMiddleware::class)
     */
    public function index(){
        return "MiddlewareController";
    }
}

```

## 应用

### 提前拦截请求

> 注意： 拦截要在 $handler->handle($request) 之前

```php
namespace App\Http\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Http\Server\Contract\MiddlewareInterface;
/**
 * @Bean()
 */
class SomeMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if ($path === '/favicon.ico') {
            $response = Context::mustGet()->getResponse();
            return $response->withStatus(404);
        }
        return $handler->handle($request);
    }
}
```

### 跨域设置

```php
namespace App\Http\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Contract\MiddlewareInterface;
/**
 * @Bean()
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('OPTIONS' === $request->getMethod()) {
            $response = Context::mustGet()->getResponse();
            return $this->configResponse($response);
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

### 用户JWT登陆验证

```php
namespace App\Http\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Http\Server\Contract\MiddlewareInterface;
/**
 * @Bean()
 */
class AuthMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // before request handle
        // 判断token
        $token = $request->getHeaderLine("token");
        $type = \config('jwt.type');
        $public = \config('jwt.publicKey');
        try {
            $auth = JWT::decode($token, $public, ['type' => $type]);
            $request->user = $auth->user;
        } catch (\Exception $e) {
            $json = ['code'=>0,'msg'=>'授权失败']
            $response = Context::mustGet()->getResponse();
            return $response->json($json);
        }
        $response = $handler->handle($request);
        return $response;
        // after request handle
    }
}

```