# 事件管理

基本的事件注册与触发管理

- implement the [Psr 14](https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md) - Event Manager
- 支持设置事件优先级
- 支持快速的事件组注册
- 支持通配符事件的监听
 
## 基本使用

```php
use Swoft\Event\EventManager;

$em = new EventManager;

// 注册事件监听
$em->attach('someEvent', 'callback_handler');


// 触发事件
$em->trigger('someEvent', 'target', ['more params']);

// 也可以
$event = new Event('someEvent', ['more params']);
$em->trigger($event);
```

## 事件分组

除了一些特殊的事件外，在一个应用中，大多数事件是有关联的，此时我们就可以对事件进行分组，方便识别和管理使用。

- **事件分组**  推荐将相关的事件，在名称设计上进行分组

例如：

```text
// 模型相关：
model.insert
model.update
model.delete

// DB相关：
db.connect
db.disconnect
db.query

// 应用相关：
app.start
app.run
app.stop
```

## 事件通配符 `*`

支持使用事件通配符 `*` 对一组相关的事件进行监听, 分两种。

1. `*` 全局的事件通配符。直接对 `*` 添加监听器(`$em->attach('*', 'global_listener')`), 此时所有触发的事件都会被此监听器接收到。
2. `{prefix}.*` 指定分组事件的监听。eg `$em->attach('db.*', 'db_listener')`, 此时所有触发的以 `db.` 为前缀的事件(eg `db.query` `db.connect`)都会被此监听器接收到。

> 当然，你在事件到达监听器前停止了本次事件的传播`$event->stopPropagation(true);`，就不会被后面的监听器接收到了。

## swoft 的全局事件管理

- 注册事件管理服务 Bean

```php
'eventManager'    => [
    'class'     => \Swoft\Event\EventManager::class,
],		     
```

- 注册事件监听

```php
\Swoft\App::getBean('eventManager')->attach('event', 'handler');

// 也可以用 @Listener("event") 来注册

/**
 * 应用加载事件
 *
 * @Listener(AppEvent::APPLICATION_LOADER)
 */
class ApplicationLoaderListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event      事件对象
     */
    public function handle(EventInterface $event)
    {
      // do something ....
    }
}
```

- 触发事件

```php
\Swoft\App::trigger();
```
