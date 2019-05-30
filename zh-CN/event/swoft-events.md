# 内置事件

swoft 内置事件，基于swoole的回调处理，扩展了一些可用server事件和启动运行时的基础以增强自定义性

## Server 事件

基于swoole的回调处理，扩展了一些可用server事件，提供更加精细化的操作空间。

- `ServerEvent::BEFORE_SETTING` 在调用 swoole server 的 `setting()` 方法之前 
- `ServerEvent::BEFORE_BIND_EVENT` 在调用 swoole server 的 `on()` 方法绑定swoole回调之前 
- `ServerEvent::BEFORE_START` 在调用 swoole server 的 `start()` 方法启动server之前 
- `ServerEvent::TASK_PROCESS_START` 仅当 swoole server 的task进程启动时触发
- `ServerEvent::WORK_PROCESS_START` 仅当 swoole server 的work进程启动时触发

```php
<?php declare(strict_types=1);

namespace Swoft\Server;

/**
 * Class ServerEvent
 *
 * @since 2.0
 */
final class ServerEvent
{
    /**
     * Before set swoole settings
     */
    public const BEFORE_SETTING = 'swoft.server.before.setting';

    /**
     * Before bind swoole events
     */
    public const BEFORE_BIND_EVENT = 'swoft.server.bind.event';

    /**
     * Swoft before start server event
     */
    public const BEFORE_START = 'swoft.server.start.before';

    /**
     * On task process start event
     */
    public const TASK_PROCESS_START = 'swoft.process.task.start';

    /**
     * On work process start event
     */
    public const WORK_PROCESS_START = 'swoft.process.work.start';

    /**
     * on user process start event
     */
    public const USER_PROCESS_START = 'swoft.process.user.start';
}
```

## Swoft 基础事件

提供了一些swoft启动初始化完成后，以及一些swoft内部的特殊事件

- `SwoftEvent::APP_INIT_COMPLETE` 当swoft初始化完成(配置已加载，容器已经初始化完成)后触发

```php
<?php declare(strict_types=1);

namespace Swoft;

/**
 * Class SwoftEvent
 * @since 2.0
 */
final class SwoftEvent
{
    /**
     * Swoft init complete
     */
    public const APP_INIT_COMPLETE  = 'swoft.init.complete';

    /**
     * Session complete
     *  - webSocket connection close
     */
    public const SESSION_COMPLETE = 'swoft.session.complete';

    /**
     * Coroutine complete
     */
    public const COROUTINE_COMPLETE = 'swoft.co.complete';

    /**
     * Coroutine destroy
     */
    public const COROUTINE_DESTROY = 'swoft.co.destroy';

    /**
     * Coroutine defer
     */
    public const COROUTINE_DEFER = 'swoft.co.defer';

    /**
     * Worker shutdown
     */
    public const WORKER_SHUTDOWN = 'swoft.worker.shutdown';
}
```

