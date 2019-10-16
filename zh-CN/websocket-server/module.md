# websocket 模块

在根据上两章安装配置好之后，就可以在 `app/WebSocket` 下创建需要的 websocket 模块来处理相关逻辑

在每个模块里允许用户处理的几个事件有 `handshake` `open` `message` `close`

## 注解

### WsModule

websocket 模块类注解tag `@WsModule`

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\WsModule`
- 作用范围： `CLASS`
- 拥有属性：
    + `path` _string_ 标明了允许ws连接的URI path. 
    + `controllers` _array_ 绑定到此模块的消息控制器类
    + `messageParser` _string_ 绑定到此模块的消息数据解析器
    + `defaultOpcode` _integer_ 此模块默认的消息数据 `opcode`

示例：

```php
/**
 * @WsModule("/echo")
 */
```

上面的注解标明了允许ws连接的URI path. 即客户端请求的ws连接类似： `ws://IP:PORT/echo`

### OnHandshake

方法注解 `@OnHandshake` 标记处理握手的方法

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\OnHandshake`
- 作用范围： `METHOD`

> 这方法是可选的。如果没有特殊的需求，可以忽略它，框架会帮你握手并响应握手成功。

必须返回含有两个元素的array

- `bool` 第一个元素的值来决定是否进行握手
- 第二个元素是response对象 - _可以在response设置一些自定义header,body等信息_

### OnOpen

在握手成功后，就会触发 open 事件. 方法注解 `@OnOpen` 标记对应方法。

> 此时开始你就可以给客户端发消息了 :)

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\OnOpen`
- 作用范围： `METHOD`
- 此方法也是可选的，可以没有

### OnMessage

通过的方法注解 `@OnMessage` 标记一个消息处理方法。

> 在此阶段你可以接收到客户端的消息和发送消息给对方.

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\OnMessage`
- 作用范围： `METHOD`
- 当你没有绑定消息控制器时，表明你想自己处理消息阶段的逻辑， **此方法是必须存在的**。
- 当你有绑定消息控制器时，框架会自动解析消息并路由到指定的消息处理方法

### OnClose

通过的方法注解 `@OnClose` 标记一个关闭连接时的处理方法。

当客户的关闭连接或者server在其他地方主动关闭连接时，就会触发此事件。

你可以在这里做一些连接关闭后的工作, 比如：记录日志，解绑用户等 ...

- 注解类： `Swoft\WebSocket\Server\Annotation\Mapping\OnClose`
- 作用范围： `METHOD`
- 此方法也是可选的，可以没有

> 注意：触发此事件时连接已被关闭，不能再给对方发消息

## 快速创建模块类

可以使用 `swoftcli` 工具来快速创建一个websocket 模块类：

- 默认生成的是支持内置路由调度的模块类

```php
php swoftcli.phar gen:wsmod chat --prefix /chat
```

- 生成用户自定义调度的模块类

```php
php swoftcli.phar gen:wsmod chat --prefix /chat --tpl-file ws-module-user
```

## 代码示例

- 这里面方法上的 server 对象都是 `Swoole\WebSocket\Server` 的实例

```php
<?php

namespace App\WebSocket;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\OnHandshake;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class EchoModule
 *
 * @WsModule("/echo")
 */
class EchoModule
{
    /**
     * 在这里你可以验证握手的请求信息
     * @OnHandshake()
     * @param Request $request
     * @param Response $response
     * @return array [bool, $response]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        return [true, $response];
    }

    /**
     * On connection has open
     *
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        server()->push($fd, 'hello, welcome! :)');
    }

    /**
     * @OnMessage()
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $server->push($frame->fd, 'I have received message: ' . $frame->data);
    }

    /**
     * On connection closed
     * - you can do something. eg. record log
     *
     * @OnClose()
     * @param Server $server
     * @param int    $fd
     */
    public function onClose(Server $server, int $fd): void
    {
        // you can do something. eg. record log, unbind user...
    }
}
```

## 客户端测试

如果你安装并启用了 devtool, 那么你可以打开页面 `IP:PORT/__devtool/ws/test` 来进行ws测试

- 填上你的ws server地址(注意不要忘了URI path)
- 然后就可以连接上ws server 并收发消息了
- 如果你在前台运行的server 你也能在运行 server的console 上看到ws连接与消息log

> 当然也可在网上找一个 ws test网页来进行测试

