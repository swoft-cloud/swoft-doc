# 事件管理

在swoft我们将事件分为三大类：

- swoole server的回调事件
- swoft server的事件，基于swoole的回调处理，扩展了一些可用事件以增强自定义性
- 应用级别内的自定义事件管理和使用

## 自定义事件

基本的事件注册与触发管理

- implement the [Psr 14](https://github.com/php-fig/fig-standards/blob/master/proposed/event-dispatcher.md) - Event dispatcher
- 支持设置事件优先级
- 支持快速的事件组注册
- 支持通配符事件的监听

> 作为核心服务组件，事件管理会自动启用

```php
'eventManager'    => [
    'class'     => \Swoft\Event\Manager\EventManager::class,
],           
```

### 注册事件监听

- 用注解tag `@Listener("event name")` 来注册用户自定义的事件监听

```php
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

> 事件名称管理推荐放置在一个单独类的常量里面，方便管理和维护

## 拓展介绍

一些关于自定义事件的拓展介绍说明

### 事件分组

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

### 事件通配符 `*`

支持使用事件通配符 `*` 对一组相关的事件进行监听, 分两种。

1. `*` 全局的事件通配符。直接对 `*` 添加监听器(`$em->attach('*', 'global_listener')`), 此时所有触发的事件都会被此监听器接收到。
2. `{prefix}.*` 指定分组事件的监听。eg `$em->attach('db.*', 'db_listener')`, 此时所有触发的以 `db.` 为前缀的事件(eg `db.query` `db.connect`)都会被此监听器接收到。

> 当然，你在事件到达监听器前停止了本次事件的传播`$event->stopPropagation(true);`，就不会被后面的监听器接收到了。

### 更多介绍

更多关于自定义事件的理解参考 https://github.com/inhere/php-event-manager/blob/master/README.md