# 事件注册

swoft提供了简便的事件管理和使用

## 注解

### Listener

事件监听器类注解tag `@Listener`

- 注解类： `Swoft\Event\Annotation\Mapping\Listener`
- 作用范围： `CLASS`
- 拥有属性：
    + `event` _string_ 要监听的事件名称
    + `priority` _int_ 此监听器的优先级，值越大越先被调用

> 注意你的类必须实现接口: `Swoft\Event\EventHandlerInterface`

### Subscriber

事件监听器类注解tag `@Subscriber`，与 `@Listener` 不同的是，允许在同一个类里处理多个事件。

- 注解类： `Swoft\Event\Annotation\Mapping\Subscriber`
- 作用范围： `CLASS`

> 注意你的类必须实现接口: `Swoft\Event\EventSubscriberInterface`

## 示例

### Listener示例

```php
<?php declare(strict_types=1);

namespace SwoftTest\Event\Testing;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class TestHandler
 * @Listener("test.evt")
 */
class TestHandler implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $pos = __METHOD__;
        echo "handle the event '{$event->getName()}' on the: $pos\n";
    }
}
```

### Subscriber示例

```php
<?php declare(strict_types=1);

namespace SwoftTest\Event\Testing;

use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Event\Listener\ListenerPriority;

/**
 * Class TestSubscriber
 * @Subscriber()
 */
class TestSubscriber implements EventSubscriberInterface
{
    public const EVENT_ONE = 'test.event1';
    public const EVENT_TWO = 'test.event2';

    /**
     * Configure events and corresponding processing methods (you can configure the priority)
     * @return array
     * [
     *  'event name' => 'handler method'
     *  'event name' => ['handler method', priority]
     * ]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            self::EVENT_ONE => 'handleEvent1',
            self::EVENT_TWO => ['handleEvent2', ListenerPriority::HIGH],
        ];
    }

    public function handleEvent1(EventInterface $evt): void
    {
        $evt->setParams(['msg' => 'handle the event: test.event1 position: TestSubscriber.handleEvent1()']);
    }

    public function handleEvent2(EventInterface $evt): void
    {
        $evt->setParams(['msg' => 'handle the event: test.event2 position: TestSubscriber.handleEvent2()']);
    }
}
```

## 触发事件

> 事件名称管理推荐放置在一个单独类的常量里面，方便管理和维护

### 方式一

使用简便。但是多个参数按顺序放入，因此获取时需要根据索引获取。

- `Swoft::trigger('event name', mixd $target, $args...)`

```php
\Swoft::trigger('event name', 'target', $arg0, $arg1);
```

获取事件参数：

```php
$target = $event->getTarget();

$arg0 = $event->getParam(0);
$arg1 = $event->getParam(1);
```

### 方式二

使用稍微麻烦一点。但是多个参数按k-v放入，获取时可以根据key获取。

- `Swoft::triggerByArray('event name', mixd $target, array $args)`

```php
\Swoft::triggerByArray('event name', 'target', [
    'arg0' => $arg0,
    'arg0' => $arg1
]);
```

获取事件参数：

```php
$target = $event->getTarget();

$arg0 = $event->getParam('arg0');
$arg1 = $event->getParam('arg1');
```
