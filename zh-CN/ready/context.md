# 上下文

Context 指的是请求上下文，这是 **请求级别** 的对象，每次请求生成一个 Context 实例。每次请求结束后上下文会自动销毁。

上下文分割了不同请求的信息，保证各个请求之间的数据不会发生混乱。

> 区别于fpm应用，在swoft中存在不同的生命周期，就有了不同的上下文 `Context`，`请求` 也不是单指Http请求。

## context接口

上下文对象都实现了基础的 `ContextInterface` 接口，因此你可以使用上下文存取 **当前请求生命周期** 的数据。

> 你可通过PHPStorm的查看实现类功能，来查看具体的上下文实现类。

```php
<?php declare(strict_types=1);

namespace Swoft\Contract;

/**
 * Class ContextInterface
 *
 * @since 2.0
 */
interface ContextInterface
{
    /**
     * Get value from context
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Set value to context
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void;

    /**
     * Set multi value to context
     *
     * @param array $map
     * [key => value]
     */
    public function setMulti(array $map): void;

    /**
     * Unset key
     *
     * @param string $key
     */
    public function unset(string $key): void

    /**
     * Clear resource
     */
    public function clear(): void;
}
```

## 如何使用

只要在协程环境中，在任何地方你都可以快速的通过 `context()/Context::get()` 获取上下文对象。

```php
context()->set('age', 1);

...

$age = context()->get('age');
```

### 使用场景

- 例如http server中，中间件认证用户后，将当前用户信息放到上下文
- 链路追踪的 `traceid` `spanid` 也是存放于上下文中的。

## 常见上下文

### http server

http server中请求上下文是 `Swoft\Http\Server\HttpContext` 的实例，它扩展了 `getRequest()` 和 `getResponse()` 方法，可以快速的获取PSR-7 接口规范的 http请求、响应对象。

```php

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
```

### ws server

- 握手请求

上下文是 `Swoft\WebSocket\Server\Context\WsHandshakeContext` 的实例。
它跟 http context 基本类似，拥有 `getRequest()` 和 `getResponse()` 方法。

- 消息请求

上下文是 `Swoft\WebSocket\Server\Context\WsMessageContext` 的实例。它也拥有自定义的扩展方法，用于获取消息请求里的数据。

```php
...

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->request->getFd();
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->request->getFrame();
    }

    /**
     * Get message object.
     * Notice: Available only during the messaging phase
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->request->getMessage();
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
```

> 注意这里的 `Request` `Response` 是指消息阶段的请求、响应对象，与打开连接时的 Http 请求对象是不同的。


