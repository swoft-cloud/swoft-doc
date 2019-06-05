# 异常处理使用

由前面的介绍我们知道，swoft里的异常处理是划分场景的。

在几个通用的server里，我们已经内置了对应场景的基础异常处理类。

## 注解

由 swoft-error 组件提供的注解标签 `Swoft\Error\Annotation\Mapping\ExceptionHandler` 提供异常处理器类的标记。

### ExceptionHandler 注解

**@ExceptionHandler** 标记一个类为异常处理器

拥有属性：

- `exceptions` 定义需要处理的异常类，需完整类名

## 匹配逻辑

在同一个场景里，可以定义多个处理器来处理不同的异常

- 在发生异常时，首先会使用异常类完整匹配来查找处理器，匹配成功就由它处理
- 完整匹配失败，则会检查异常类是否是已注册的异常类的子类，是就选择第一个匹配的处理器处理
- 仍然匹配失败，则会由系统默认处理

## Http Request 异常处理

你的异常类需要继承 `Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler`

> 在处理异常后你必须返回一个 `Swoft\Http\Message\Response` 对象作为对http客户端的响应。

### 使用示例

这个示例代码来自 [swoft/swoft](https://github/swoft-cloud/swoft) 的 `app/Exception/Handler/HttpExceptionHandler`

```php
<?php declare(strict_types=1);

namespace App\Exception\Handler;

use const APP_DEBUG;
use function get_class;
use ReflectionException;
use function sprintf;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Throwable;

/**
 * Class HttpExceptionHandler
 *
 * @ExceptionHandler(\Throwable::class)
 */
class HttpExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response   $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(Throwable $e, Response $response): Response
    {
        // Debug is false
        if (!APP_DEBUG) {
            return $response->withStatus(500)->withContent(
                sprintf(' %s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        $data = [
            'code'  => $e->getCode(),
            'error' => sprintf('(%s) %s', get_class($e), $e->getMessage()),
            'file'  => sprintf('At %s line %d', $e->getFile(), $e->getLine()),
            'trace' => $e->getTraceAsString(),
        ];

        // Debug is true
        return $response->withData($data);
    }
}
```

## RPC 异常处理

你的异常类需要继承 `Swoft\Rpc\Server\Exception\Handler\AbstractRpcServerErrorHandler`

> 在处理异常后你必须返回一个 `Swoft\Rpc\Server\Response` 对象作为对rpc客户端的响应。

### 使用示例

这个示例代码来自 [swoft/swoft](https://github/swoft-cloud/swoft) 的 `app/Exception/Handler/RpcExceptionHandler`

```php
<?php declare(strict_types=1);

namespace App\Exception\Handler;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Log\Debug;
use Swoft\Rpc\Error;
use Swoft\Rpc\Server\Exception\Handler\RpcErrorHandler;
use Swoft\Rpc\Server\Response;
use Throwable;

/**
 * Class RpcExceptionHandler
 *
 * @since 2.0
 *
 * @ExceptionHandler(\Throwable::class)
 */
class RpcExceptionHandler extends RpcErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(Throwable $e, Response $response): Response
    {
        // Debug is false
        if (!APP_DEBUG) {
            $message = sprintf(' %s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine());
            $error   = Error::new($e->getCode(), $message, null);
        } else {
            $error = Error::new($e->getCode(), $e->getMessage(), null);
        }

        Debug::log('Rpc server error(%s)', $e->getMessage());

        $response->setError($error);

        // Debug is true
        return $response;
    }
}
```

## 使用说明

```php
class BusinessLogic 
{
    public function doSomething()
    {
        throw new BusinessException("Error Processing Request", 500);
    }
}
```

当这里抛出异常：

- 在http request 场景范围内，它会被 `HttpExceptionHandler` 处理。
- 若是在RPC server 请求场景里，则会由 `RpcExceptionHandler` 处理。

> 上面的处理器是通过查找异常父类找到的，当然你也可以定义一个针对 `BusinessException` 的异常处理器 `@ExceptionHandler(BusinessException::class)`

## 结语

通过上面的示例我们可以看到，即使你是同一个地方抛出的异常，只要你定义了不同场景的异常处理器。
就可以分别针对不同场景的请求(如上面的 http 和 rpc)，在里面返回不同的响应。无需做额外的检查与判断。


> 更多场景的异常处理也可以参考上面的示例来编写。

