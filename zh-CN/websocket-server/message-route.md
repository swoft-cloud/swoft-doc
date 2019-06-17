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

> **注意** 这里定义Ws模块时，绑定了一个框架自带的消息解析器，`TokenTextParser::class`  内置了一个decode 的方法用来解析数据

```php
    // 默认为字符串解析，消息路由格式 `控制器.方法:数据`
    public function decode(string $data): Message
    {
        // use default message command
        $cmd = '';
        if (strpos($data, ':')) {
            [$cmd, $body] = explode(':', $data, 2);
            $cmd = trim($cmd);
        } else {
            $body = $data;
        }

        return Message::new($cmd, $body);
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

### 访问

根据以上定义好的 `Ws模块`、`消息解析器`、`消息控制器` 等内容后启动我们的服务。然后打开webscoket 调试工具，链接Ws的地址： `ws://localhost:port/chat ` 然后测试发送一个内容

```text
    Send: testWS
    Recv: hi, this is home.index
    Send: home.echo:这是数据
    Recv: (home.echo)Recv: 这是数据
```
