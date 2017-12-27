# 事件管理

 事件管理参考使用 [Psr 14](https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md) 实现。
 
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
