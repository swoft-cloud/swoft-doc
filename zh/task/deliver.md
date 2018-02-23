# 任务投递
任务可投递协程和异步，如果是异步需要定义监听器，处理任务执行结果数据。

## 实例

```php
$result  = Task::deliver('demo', 'deliverCo', ['p', 'p2'], Task::TYPE_CO);

$result  = Task::deliver('demo', 'deliverAsync', ['p', 'p2'], Task::TYPE_ASYNC);
```

- 函数第一个参数，定义投递任务组名称，与@Task注解定义的名称关系对应。
- 函数第二个参数，定义投递任务的名称，与类里面方法对应
- 函数第三个参数，数组方式传递，函数参数
- 函数第四个参数，定义投递任务类型

## 异步任务监听器

事件监听器必须监听，TaskEvent::FINISH_TASK事件。

```php
/**
 * Task finish handler
 *
 * @Listener(TaskEvent::FINISH_TASK)
 */
class TaskFinish implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        var_dump("task finish! ", $event->getParams());
    }
}
```