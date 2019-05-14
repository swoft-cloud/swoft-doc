# 协程任务

协程任务投递提供了两种方式，单个投递和批量投递，单个投递是在批量投递的基础之上封装的。如下协程任务投递：

```php
use Swoft\Task\Task;

// 协程投递
$data = Task::co('testTask', 'list', [12]);

// 协程投递
$result = Task::co('testTask', 'delete', [12]);
```


## 任务投递

```php
Task::co(string $name, string $method, array $params = [], float $timeout = 3, array $ext = [])
```

单个任务投递，返回数据和任务方法返回的数据完全一致类型也一样

- name 投递任务任务名称
- method 投递任务的方法名称
- params 任务传递的参数即是任务方法的参数，数组格式传递
- timeout 超时时间，默认 3s 超时
- ext 任务扩展信息，会传递给任务进程里面


```php
// 任务格式
$tasks = [
    [
        'taskName',
        'method',
        ['params']
    ]
];

Task::cos(array $tasks, float $timeout = 3, array $ext = [])
```

- tasks 多个任务集合，格式如上
- timeout 超时时间，默认 3s 超时
- ext 任务扩展信息，会传递给任务进程里面

## 任务上下文

有些场景需要在任务里面拿到任务的详细信息，这些信息全部在上下文里面。此时可以使用全局函数 `context()` 获取 `Swoft\Task\TaskContext` 上下文对象。上下文提供两个方法，分别获取 `Swoft\Task\Request` 与 `Swoft\Task\Response` 对象，里面包含投递任务的所有信息。

```php
$request = context()->getRequest();
$response = context()->getRespone();
```

> 注意：一定要在任务里面获取上下文，否则获取的是其它环境的上下文

### 任务 Request


```php
namespace Swoft\Task;

class Request implements RequestInterface
{
   // ...
}
```

方法列表

- getServer 获取任务 Server 信息
- getTaskId 获取任务 ID，对应 Swoole 任务 ID
- getSrcWorkerId 任务来自的 workerId
- getData 投递任务的原始是数据
- getName 任务名称
- getMethod 任务方法
- getParams 任务参数
- getExt 任务扩展信息
- getExtKey 根据 key 快速获取用户信息
- getType 任务类型
- getTaskUniqid 任务全局唯一ID