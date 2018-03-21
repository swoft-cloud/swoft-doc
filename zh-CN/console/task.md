# 任务投递

console命令行里面，如果有需要可以通过 Http 和内置队列模式投递任务，队列模式只支持本机。队列模式投递任务，swoft-task 组件版本必须不小 `v1.0.2-beta`.

## 升级指南

### 更新组件

```bash
composer update
```

## 升级配置

app/config/server.php新增队列配置    

```php
return [
    // ....
    'setting' => [
        'task_ipc_mode'         => env('TASK_IPC_MODE', 3),
        'message_queue_key'     => env('MESSAGE_QUEUE_KEY', 0x70001001),
        'task_tmpdir'           => env('TASK_TMPDIR', '/tmp'),
    ],
];
```

.env 新增配置信息    

```ini
TASK_IPC_MODE=3
MESSAGE_QUEUE_KEY=1879052289
TASK_TMPDIR=/tmp/
```


## 实例

任务投递和普通操作没有区别

```
/**
 * Test command
 *
 * @Command(coroutine=false)
 */
class TestCommand
{
    /**
     * this task command
     *
     * @Usage
     *   {fullCommand} [arguments] [options]
     *
     * @Options
     *   -o,--opt  This is command option
     *
     * @Arguments
     *   arg    This is argument
     *
     * @Example
     *   {fullCommand}
     *
     * @Mapping()
     */
    public function task()
    {
        $result = Task::deliver('sync', 'console', ['console']);
        var_dump($result);
    }
}
```

> 只支持console 非协程投递任务，如果是协程只能通过Http或RPC投递任务
