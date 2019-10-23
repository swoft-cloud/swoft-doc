# 上下文

Context 指的是请求上下文，这是 **请求级别** 的对象，每次请求生成一个 Context 实例。每次请求结束后上下文会自动销毁。

上下文分割了不同请求的信息，保证各个请求之间的数据不会发生混乱。

> 区别于fpm应用，在swoft中存在不同的生命周期，就有了不同的上下文 `Context`。

## context接口

上下文对象都实现了基础的 `ContextInterface` 接口，因此你可以使用上下文存取 **当前请求生命周期** 的数据。

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

## 常见上下文

- http server 中请求上下文是 `Swoft\Http\Server\HttpContext` 的实例。它扩展了 `getRequest()` 和 `getResponse()` 方法，可以快速的获取请求、响应对象 
