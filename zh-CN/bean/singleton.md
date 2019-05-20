# singleton

单例Bean全局只有一个共享的实例，所有将单例Bean作为依赖的情况下，容器返回将是同一个实例。

换言之，当开发者定义一个Bean的作用域为单例时，Swoft IOC容器只会根据Bean定义来创建该Bean的唯一实例。这些唯一的实例会缓存到容器中，后续针对单例Bean的请求和引用，都会从这个缓存中拿到这个唯一的实例。

## 简介
`singleton`的`bean` 会在框架`启动时`被初始化，并且只会初始化一次。
对`singleton`的 `bean` 进行写入操作是不安全的。如果同时读写`singleton`的 `bean`，会造成上下文切换导致`bean`数据不一致，这种往往是业务交叉造成的。。
 
## 特别注意
`singleton` 一般用于`只读`， 不要把它当做共享内存的方式。

例如 `swoft` 的 `config bean`，就是一个全局单例的。它只用于读取，在运行过程中不会发生改变。系统把`config` 写操作都抛出了异常。

## 定义

```php

<?php declare(strict_types=1);

namespace Swoft\Config;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Config
 *
 * @Bean("config")
 *
 * @since 2.0
 */
class Config
{
}
```

默认是 `score` 是全局`单例`的，创建单例的`bean`，你可以不用指定`score`。

## 销毁
`singleton` 类型的 `bean` 只会在 `主进程关闭` 才会被销毁。
