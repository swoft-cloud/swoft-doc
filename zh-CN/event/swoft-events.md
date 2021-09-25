# 内置事件

swoft 内置事件，基于swoole的回调处理，扩展了一些可用server事件和启动运行时的基础以增强自定义性

## Server 事件

基于swoole的回调处理，扩展了一些可用server事件，提供更加精细化的操作空间。

- `ServerEvent::BEFORE_SETTING` 在调用 swoole server 的 `setting()` 方法之前 
- `ServerEvent::BEFORE_BIND_EVENT` 在调用 swoole server 的 `on()` 方法绑定swoole回调之前
- `ServerEvent::BEFORE_BIND_LISTENER` 在调用 swoole server 的 `listen()` 方法添加多端口监听之前
- `ServerEvent::AFTER_ADDED_LISTENER` 每当调用 swoole server 的 `listen()` 方法成功添加一个端口监听之后(添加多个端口简单，则会调用多次)
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
     * Before bind listener(s)
     */
    public const BEFORE_BIND_LISTENER = 'swoft.server.bind.listener.before';

    /**
     * After each listener is successfully added
     */
    public const AFTER_ADDED_LISTENER = 'swoft.server.added.listener.after';

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

### 使用示例

我们可以在swoole server启动前注册一个自定义进程，这样可以让进程由server托管

- 不需要执行start。在Server启动时会自动创建进程，并执行指定的子进程函数
- 在shutdown关闭服务器时，会向用户进程发送SIGTERM信号，关闭用户进程
- 自定义进程会托管到Manager进程，如果发生致命错误，Manager进程会重新创建一个

```php
<?php declare(strict_types=1);

namespace App\Listener;

use App\Process\MyProcess;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\ServerEvent;

/**
 * Class AttachMyProcessHandler
 * @Listener(ServerEvent::BEFORE_START)
 */
class AttachMyProcessHandler implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $swooleServer = $event->target->getSwooleServer();
        
        $process = bean(MyProcess::class);
        
        $swooleServer->addProcess($process->create());
    }
}
```

```php
<?php declare(strict_types=1);

namespace App\Process;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoole\Process;

/**
 * Class MyProcess
 * @Bean()
 */
class MyProcess
{
    public function create(): Process
    {
        $process = new Process([$this, 'handle']);
        
        return $process;
    }
    
    public function handle(Process $process)
    {
        CLog::info('my-process started');
        
        // 用户进程实现了广播功能，循环接收管道消息，并发给服务器的所有连接
        while (true) {
            $msg = $process->read();
            foreach($server->connections as $conn) {
                $server->send($conn, $msg);
            }
        }
    }
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

