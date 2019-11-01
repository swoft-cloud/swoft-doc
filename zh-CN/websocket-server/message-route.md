# 消息处理

swoft 提供了灵活的 websocket 使用，支持自定义和由框架托管处理消息两种方式。

- 如果你在ws模块类没有添加 `@OnMessage` 处理方法，框架将会自动托管这个阶段，解析消息并根据路由分发到不同的方法执行
- 如果你在ws模块类里面绑定了 `@OnMessage` 处理方法，swoft就认为你想自己处理这个阶段，框架就不会处理了

> 注意：本篇文档的使用是建立在由框架托管消息路由的基础上。

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

> 完整的消息路由path是 上面的 `preifx` 和 `command` 由点拼接而成 `PREFIX.COMMAND`

## 消息解析

不同的使用者或者使用场景，用于ws通信的数据格式可能是不一样的。因此，在编写ws模块时，需要你绑定消息解析器。

### 内置解析器

- `Swoft\WebSocket\Server\MessageParser\RawTextParser` 简单的字符串
- `Swoft\WebSocket\Server\MessageParser\TokenTextParser` 简单的token字符串协议(_方便测试使用的_)
- `Swoft\WebSocket\Server\MessageParser\JsonParser` 简单的 JSON 数据协议

JSON 协议通信数据结构：

```json
{
    "cmd": "message route path. eg: home.index", // type: string
    "data": "message data", // type: mixed(array|string|int)
    "ext": {"ip": "xx", "os": "mac"}, // optional, type: array
}
```

## 获取数据

有多种方式可以获取消息请求的数据信息。

Message 对象是一个通用的websocket数据对象，里面保存了解析后的数据，包含 `cmd` `data` `ext` 三个字段。

### 通过参数注入

```php
use Swoft\WebSocket\Server\Message\Message;
use Swoft\WebSocket\Server\Message\Request;

...
// inject raw frame data string
public function autoReply(string $data): string
{
    return $data;
}

// inject Message object
public function autoReply(Message $msg): string
{
    return $msg->toString();
}

// inject Request object
public function autoReply(Request $req): string
{
    return $req->getMessage()->toString();
}
```

### 通过上下文获取

```php
use Swoft\WebSocket\Server\Message\Message;

...

public function autoReply(): string
{
    $msg = context()->getMessage();
}
```

更多获取方式：

```php
use Swoft\WebSocket\Server\Message\Request;

$req = context()->getRequest();

/** @var \Swoft\WebSocket\Server\Message\Message $msg */
$msg = $req->getMessage();

/** @var \Swoole\WebSocket\Frame $frame */
$frame = $req->getFrame();
```

> 注意这里的 `Request` 是指消息阶段的请求对象，与打开连接时的请求对象是不同的。

## 使用示例

### 定义ws模块

**注意** 要绑定消息处理控制器，通常也需要绑定你的消息解析器，可以使用内置的几个简单的解析器，也可以根据需要自定义。

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

- 定义的ws模块路径为 `/chat`
- 绑定了的控制器有： `HomeController::class` **你可以绑定多个控制器**
- 绑定了一个内置的消息解析器

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
     * @param string $data
     * @MessageMapping()
     */
    public function echo(string $data): void
    {
        Session::mustGet()->push('(home.echo)Recv: ' . $data);
    }

    /**
     * Message command is: 'home.ar'
     *
     * @param string $data
     * @MessageMapping("ar")
     *
     * @return string
     */
    public function autoReply(string $data): string
    {
        return '(home.ar)Recv: ' . $data;
    }
}
```

> 注意，自 `v2.0.6` 版本起，通过参数注入接收websocket原始数据时，需要加上类型 `string`。例如： `public function echo(string $data)`

### 访问服务

根据以上定义好的 `Ws模块`、`消息解析器`、`消息控制器` 等内容后启动我们的服务。然后打开webscoket 调试工具，链接Ws的地址： `ws://localhost:port/chat ` 然后测试发送一个内容

```text
    Send: testWS
    Recv: hi, this is home.index
    Send: home.echo:这是数据
    Recv: (home.echo)Recv: 这是数据
```
