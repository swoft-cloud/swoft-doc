# swoole 事件

swoole文档上列出的每个事件，在swoft里面都可以监听，并且可以有多个监听器。

## 事件常量

swoft 里我们为这些事件名添加了常量，方便统一使用和管理。

```php
<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

/**
 * Class SwooleEvent
 *
 * @since 2.0
 */
final class SwooleEvent
{
    /**
     * Start
     */
    public const START = 'start';

    /**
     * Shutdown
     */
    public const SHUTDOWN = 'shutdown';

    /**
     * WorkerStart
     */
    public const WORKER_START = 'workerStart';

    /**
     * WorkerStop
     */
    public const WORKER_STOP = 'workerStop';

    /**
     * WorkerError
     */
    public const WORKER_ERROR = 'workerError';

    /**
     * ManagerStart
     */
    public const MANAGER_START = 'managerStart';

    /**
     * ManagerStop
     */
    public const MANAGER_STOP = 'managerStop';

    /**
     * Task
     */
    public const TASK = 'task';

    /**
     * Finish
     */
    public const FINISH = 'finish';

    /**
     * PipeMessage
     */
    public const PIPE_MESSAGE = 'pipeMessage';

    /**
     * Handshake
     */
    public const HANDSHAKE = 'handshake';

    /**
     * Message
     */
    public const MESSAGE = 'message';

    /**
     * Open
     */
    public const OPEN = 'open';

    /**
     * Request
     */
    public const REQUEST = 'request';

    /**
     * Packet
     */
    public const PACKET = 'packet';

    /**
     * Receive
     */
    public const RECEIVE = 'receive';

    /**
     * Connect
     */
    public const CONNECT = 'connect';

    /**
     * Close
     */
    public const CLOSE = 'close';

    /**
     * BufferFull
     */
    public const BUFFER_FULL = 'bufferFull';

    /**
     * BufferEmpty
     */
    public const BUFFER_EMPTY = 'bufferEmpty';
}
```

## 监听swoole事件

这里我们监听 `master` 进程启动时的事件，要监听其他事件也是类似写法。

```php
<?php declare(strict_types=1);

namespace App\Listener;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class MasterStartListener
 *
 * @Listener(SwooleEvent::START)
 */
class MasterStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(EventInterface $event): void
    {
        CLog::info('master started');
    }
}
```

