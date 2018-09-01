# 任务投递
任务可投递协程和异步，如果是异步需要定义监听器，处理任务执行结果数据。

## 实例

```php
use Swoft\Task\Task;

$result  = Task::deliver('demo', 'coroutineJob', ['p1', 'p2'], Task::TYPE_CO);
$result  = Task::deliver('demo', 'asyncJob', ['p1', 'p2'], Task::TYPE_ASYNC);
```

- 参数一，定义投递任务组名称，与@Task注解定义的名称(`name`)关系对应。
- 参数二，定义投递任务的名称，与类里面方法名称对应
- 参数三，任务方法的参数，数组方式传递
- 参数四，定义投递任务类型，仅 Task::TYPE_CO 和 Task::TYPE_ASYNC 两个可选项

## 异步任务监听器

事件监听器必须监听，`TaskEvent::FINISH_TASK`事件。

```php
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Task\Event\TaskEvent;

/**
 * @Listener(TaskEvent::FINISH_TASK)
 */
class TaskFinish implements EventHandlerInterface
{

    public function handle(EventInterface $event)
    {
        var_dump("task finish! ", $event->getParams());
    }
}
```
