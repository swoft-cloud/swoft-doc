# 消息处理

如果你定义的ws模块类没有添加 `OnMessage` 处理方法，框架将会自动托管这个阶段，解析消息并根据路由分发到不同的方法执行。

## 注解

### WsController

websocket 消息控制器注解tag `@WsController`

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\WsController`
- 作用范围： `CLASS`
- 拥有属性：
    + `prefix` _string_ 消息路由前缀

### MessageMapping

方法注解 `@MessageMapping` 标记具体的消息处理方法，类似于http控制器里的action。

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping`
- 作用范围： `METHOD`
- 拥有属性：
    + `command` _string_ 消息命令名称

## 说明

完整的消息路由path是 上面的 `preifx` 和 `command` 由点拼接而成 `PREFIX.COMMAND`

## 示例

### 定义ws模块

**注意** 要绑定消息处理控制器，通常也需要绑定你的消息解析器，内置了几个简单的解析器。

```php
<?php declare(strict_types=1);

namespace App\WebSocket;

use App\WebSocket\Chat\HomeController;
use Swoft\Http\Message\Request;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\MessageParser\TokenTextParser;
use function server;

/**
 * Class ChatModule
 *
 * @WsModule(
 *     "/chat",
 *     messageParser=TokenTextParser::class,
 *     controllers={HomeController::class}
 * )
 */
class ChatModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        server()->push($request->getFd(), "Opened, welcome!(FD: $fd)");
    }
}
```

### 消息控制器

**注意** 必须使用注解 `@WsController` 以及 `@MessageMapping`

```php
<?php declare(strict_types=1);

namespace App\WebSocket\Chat;

use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class HomeController
 *
 * @WsController()
 */
class HomeController
{
    /**
     * Message command is: 'home.index'
     *
     * @return void
     * @MessageMapping()
     */
    public function index(): void
    {
        Session::mustGet()->push('hi, this is home.index');
    }

    /**
     * Message command is: 'home.echo'
     *
     * @param $data
     * @MessageMapping()
     */
    public function echo($data): void
    {
        Session::mustGet()->push('(home.echo)Recv: ' . $data);
    }

    /**
     * Message command is: 'home.ar'
     *
     * @param $data
     * @MessageMapping("ar")
     *
     * @return string
     */
    public function autoReply($data): string
    {
        return '(home.ar)Recv: ' . $data;
    }
}
```
