# 异常处理

通常我们把异常类放置 `app/Exception` ，异常类处理器放置 `app/Exception/Handler` 异常分为两部分。自定义的 `Exception` 异常类，异常处理类 `ExceptionHandler`

## 定义异常类

在不同应用场景下，定义不同的异常类，如需要一个控制器抛异常的类

app/Exception/ControllerException.php

```php
namespace App\Exception;
class ApiException extends \Exception
{

}
```

## 定义异常处理类

```php
namespace App\Exception\Handler;
use App\Exception\ApiException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
/**
 * @ExceptionHandler(ApiException::class)
 */
class ApiExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param \Throwable $e
     * @param Response $response
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(\Throwable $e, Response $response): Response
    {
        $data = ['code'=>-1,'msg'=>$e->getMessage()];
        return $response->withData($data);
    }
}
```

## 注解

### ExceptionHandler

异常处理程序，指定这个处理器要处理当异常，当程序抛出 `ExceptionHandler` 注解里有的异常将会自动执行 `handle` 方法

- 指定异常：参数可以是字符串也可以是数组

处理一个异常
```php
@ExceptionHandler(ApiException::class)
```

处理多个异常
```php
@ExceptionHandler({ApiException::class,ServiceException::class})
```
