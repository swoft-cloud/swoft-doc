# 内置事件

swoft server的事件，基于swoole的回调处理，扩展了一些可用server事件和启动运行时的基础以增强自定义性

## Server 事件

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

