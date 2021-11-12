# 消息发送

上一节我们知道了如何创建ws模块，并通过客户端连接到server。

可以从示例代码里看到有简单的消息发送使用了。

```php
... 
/** @var \Swoole\WebSocket\Server $server */
$server->push($fd, 'hello, welcome! :)');
...
```

- 这里的server是swoole的 `\Swoole\WebSocket\Server` 对象
- `$fd` 是与客户端的连接 ID，它表明了不同的客户端

### 使用 `\server()`

除了使用 `$server` 来发送消息外,我们还可以使用swoft封装好的 `\server()` 或者 `\Swoft::server()` 来发送消息.

例如：

```php
\server()->sendTo($fd, 'hi, 你好啊！');
\Swoft::server()->sendTo($fd, 'hi, 你好啊！');
```

说明： 

- 是 `Swoft\WebSocket\Server\WebSocketServer` 的实例对象
- 内部已经封装了各种发送消息的方法API
- 前台运行时，通过它发送消息能从控制台看到消息发送log

## 消息发送API

注意下面的方法都在类： `Swoft\WebSocket\Server\WebSocketServer`

### 发送给某个客户端

```php
public function sendTo(int $receiver, string $data, int $sender = 0): int
```

参数说明：

- `$receiver` `int` 接收者的fd
- `$data` `string` 要发送的消息数据
- `$sender` `int` 发送者的fd。 _可选的_

示例：

```php
\server()->sendTo($fd, 'hi, 你好啊！');
```

### 发送给指定的一些客户端

```php
public function sendToSome(string $data, array $receivers = [], array $excluded = [], int $sender = 0, int $pageSize = 50): int
```

参数说明：

- `$data` `string` 要发送的消息数据
- `$receivers` `int[]` 指定的接收者fd 列表
- `$excluded` `int[]` 排除的接收者fd 列表
- `$sender` `int` 发送者的fd。 _可选的_

方法说明：

- 当 `$receivers` 有数据时，将会忽略 `$excluded`。 此时就是将消息指定的发给这些接收者
- 当 `$receivers` 为空时
	- 若 `$excluded` 有值，将会给除了这些人之外的发送消息
	- 若 `$excluded` 为空，相当于给所有人发消息

示例：

```php
\server()->sendToSome('hi, 你们好啊！', [$fd0, $fd1, ...]);
```

### 广播消息

发送消息给除了 `sender` 外的所有人。使用分页方式发送，每 50 个一页，直到全部发送完毕

```php
broadcast(string $data, array $receivers = [], array $excluded = [], int $sender = 0): int
```

### 发送给所有客户端

```php
public function sendToAll(string $data, int $sender = 0, int $pageSize = 50): int
```

发送消息给所有客户端，相当于进行全员广播。使用分页方式发送，每 50 个一页，直到全部发送完毕

参数说明：

- `$data` `string` 要发送的消息数据
- `$sender` `int` 发送者的fd。 _可选的_

示例：

```php
\server()->sendToAll('hi, 大家好啊！');
```

### send

**参数跟 `sendToSome` 一样**

会自动根据参数判断调用上面的（`sendTo`, `sendToAll`, `sendToSome`）中的一个方法


## 断开连接

服务端可以主动断开连接，断开后会触发 `close` 事件

```php
bean('wsServer')->disconnect($fd);

// OR
server()->disconnect($fd);
```
