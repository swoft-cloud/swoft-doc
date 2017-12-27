# 中间件

通过中间件，可以实现在请求到达最终动作之前或之后，对请求进行过滤和处理。Swoft 中一个 HTTP 请求或 RPC 函数调用都是通过多个中间件实现的，使用者也可以自定义一些特殊功能的中间件，比如权限验证、参数验证、接口限制等等。

## 定义中间件

Swoft 中通过实现`Swoft\Middleware\MiddlewareInterface`接口，并实现`process`方法来定义一个中间件。下面分别定义一个请求之前处理和请求之后使用实例。

```php
/**
 * 请求之前
 *
 * @Bean()
 */
class FaviconIcoMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface     $request
     * @param \Interop\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Fix Chrome ico request bug
        if ($request->getUri()->getPath() == '/favicon.ico') {
            throw new NotAcceptableException();
        }

        return $handler->handle($request);
    }
}

/**
 * 请求之后
 * 
 * @Bean()
 */
class ControllerSubMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface     $request
     * @param \Interop\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withAddedHeader('ControlerSubMiddleware', 'success');
    }
}
```
