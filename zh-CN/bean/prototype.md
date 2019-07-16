# prototype

`prototype` 用的是`原型模式`, 它会被框架启动时会被自动初始化.

## 原型模式
获取 `scope`为`prototype`类型的`bean`每次都是`克隆`初始化好的`bean`。

clone 一个对象 比 重新`new`一个对象更快, 因为它是拷贝操作。 

在 `swoft` 中 DB 的`collection` 就是用的`prototype`类型

## 如何使用

你可以定义一个 `new` 方法，替代`new`关键字

比如`Db`使用的`Collection Prototype` ：

> 实体都是 `prototype`，类型的`bean`，所有实体都可以使用`new`方法。

```php
<?php declare(strict_types=1);


namespace Swoft\Test;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;

/**
 * Class TestCollection
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class TestCollection
{
    use PrototypeTrait;
    
    /**
     * @var array
     */
    private $items;

    /**
     * Create a new collection.
     *
     * @param array $items
     *
     * @return static
     */
    public static function new(array $items = []): self
    {
        $self        = self::__instance();
        $self->items = $items;

        return $self;
    }
}
```

需要引入`PrototypeTrait`，在`PrototypeTrait` 中实现了 `__instance()`方法，该返回的就是一个 clone 的自身对象，你只需对应更新属性值 即可获取一个全新的对象。
