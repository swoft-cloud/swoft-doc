# websocket 控制器

在根据上两章安装配置好之后，就可以在 `app/WebSocket` 下创建需要的 websocket 控制器来处理相关逻辑

## 注解tag

websocket 新增了类注解tag `@WebSocket`

- 说明

```php
/**
 * @WebSocket("/echo")
 */
```

上面的注解标明了允许ws连接的URI path. 即客户端请求的ws连接类似： `ws://IP:PORR/echo`

## 示例

```php
<?php

namespace App\WebSocket;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\WebSocket\Server\Bean\Annotation\WebSocket;
use Swoft\WebSocket\Server\HandlerInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class EchoController
 * @package App\WebSocket
 * @WebSocket("/echo")
 */
class EchoController implements HandlerInterface
{
    /**
     * 在这里你可以验证握手的请求信息
     * - 必须返回含有两个元素的array
     *  - 第一个元素的值来决定是否进行握手
     *  - 第二个元素是response对象
     * - 可以在response设置一些自定义header,body等信息
     * @param Request $request
     * @param Response $response
     * @return array
     * [
     *  self::HANDSHAKE_OK,
     *  $response
     * ]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        return [self::HANDSHAKE_OK, $response];
    }

    /**
     * @param Server $server
     * @param Request $request
     * @param int $fd
     */
    public function onOpen(Server $server, Request $request, int $fd)
    {
        $server->push($fd, 'hello, welcome! :)');
    }

    /**
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $server->push($frame->fd, 'hello, I have received your message: ' . $frame->data);
    }

    /**
     * on connection closed
     * @param Server $server
     * @param int $fd
     */
    public function onClose(Server $server, int $fd)
    {
        // you can do something. eg. record log
    }
}
```
