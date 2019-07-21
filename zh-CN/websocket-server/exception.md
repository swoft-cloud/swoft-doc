# 异常处理

前面我们了解了系统如何处理异常，以及http server里如何处理异常的。在websocket server 也是类似的，我们只需定义websocket相关场景的异常处理器就行。

与http server 里只有一个 request 场景不同, websocket 里有四个场景：

- `handshake` 握手环节
- `open` 握手后连接打开
- `message` 消息通信阶段
- `close` 连接关闭

下面我们编写 websocket 几个环节中最重要的 握手 和 消息通信 环节的异常处理。其他环节的可以参考和继承相关类来编写。

## 握手异常

因为websocket握手环节就是http请求处理，所以此环节的异常跟http里处理是一样的，当然你还是得继承为这个场景设计的基础类才行。

- 必须继承 `AbstractHandshakeErrorHandler` 类，我们才能知道你要处理哪个场景里的异常

```php
<?php declare(strict_types=1);

namespace App\Exception\Handler;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Exception\Handler\AbstractHandshakeErrorHandler;
use Throwable;
use function get_class;
use function sprintf;
use const APP_DEBUG;

/**
 * Class HttpExceptionHandler
 *
 * @ExceptionHandler(\Throwable::class)
 */
class WsHandshakeExceptionHandler extends AbstractHandshakeErrorHandler
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
            return $response->withStatus(500)->withContent(sprintf(
                '%s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine()
            ));
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

## 消息通信异常

在握手成功后的消息通信阶段出现异常，也可以方便的捕获处理。

> 注意：你仍然需要继承专有场景的异常处理抽象类 `AbstractMessageErrorHandler`

```php
<?php declare(strict_types=1);

namespace App\Exception\Handler;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Log\Helper\Log;
use Swoft\WebSocket\Server\Exception\Handler\AbstractMessageErrorHandler;
use Swoole\WebSocket\Frame;
use Throwable;
use function server;
use const APP_DEBUG;

/**
 * Class WsMessageExceptionHandler
 *
 * @since 2.0
 *
 * @ExceptionHandler(\Throwable::class)
 */
class WsMessageExceptionHandler extends AbstractMessageErrorHandler
{
    /**
     * @param Throwable $e
     * @param Frame     $frame
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(Throwable $e, Frame $frame): void
    {
        $message = sprintf('%s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine());

        Log::error('Ws server error(%s)', $message);

        // Debug is false
        if (!APP_DEBUG) {
            server()->push($frame->fd, $e->getMessage());
            return;
        }

        server()->push($frame->fd, $message);
    }
}
```

