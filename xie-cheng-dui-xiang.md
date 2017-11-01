# 协程对象

协程对象主要用于获取协程ID，创建子协程，其实是对swoole底层的一个封装。类名\Swoft\Base\Coroutine

```php
// 创建子协程
Coroutine::create(function (){
    App::error("child cor error msg");
    App::trace("child cor error msg");
});

// 当前协程id
$cid = Coroutine::id();

// 当前运行上下文ID, 协程环境中，顶层协程ID; 任务中，当前任务taskid; 自定义进程中，当前进程ID(pid)
$tid = Coroutine::tid();
```



