# 异步任务

异步任务一般用于不需要结果的场景且异步区执行，不影响主流程。如下异步任务投递：

```php
use Swoft\Task\Task;

$data = Task::async('testTask', 'list', [12]);

$data = Task::async('testTask', 'delete', [12]);
```

## 任务投递

```php
Task::async(string $name, string $method, array $params = [], array $ext = [], int $dstWorkerId = -1, callable $fallback = null)
```
异步任务投递，返回一个全局唯一的任务ID

- name 投递任务任务名称
- method 投递任务的方法名称
- params 任务传递的参数即是任务方法的参数，数组格式传递
- ext 任务扩展信息，会传递给任务进程里面
- dstWorkerId 投递的进程 workerId，默认底层按需选择进程 workerId

## 任务上下文

任务上下文和协程任务章节讲解的完全一样

## 异步任务结果

有很多情况不需要关注异步任务处理结果，但是也有部分场景需要关注异步任务处理结果，框架为开发者提供了一种事件监听的方式来处理异步任务结果。此事件和普通事件完全一样。如下定义是事件监听：

```php
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Task\TaskEvent;

/**
 * Class FinishListener
 *
 * @since 2.0
 *
 * @Listener(event=TaskEvent::FINISH)
 */
class FinishListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        CLog::info(\context()->getTaskUniqid());
    }
}
```

- 事件必须监听 `TaskEvent::FINISH`
- 如果需要获取数据可以从上下文中获取，注意此时获取的是任务完成的上下文对象与任务上下对象不一样。

## 异步任务完成上下文

在异步任务完成监听器里面可以通过 `content()` 全局函数获取上下文 `Swoft\Task\FinishContext` 对象。

```php
$taskData = context()->getTaskData();

$taskId = context()->getTaskId();

$taskUniqid = context()->getTaskUniqid();

$server = context()->getServer();
```

- getTaskData 任务处理的结果内容
- getTaskId 任务 ID，对应 Swoole 任务 ID
- getTaskUniqid 全局任务唯一ID，框架生成，与任务投递时的全局任务ID一样
- getServer 获取任务 Server 相关信息