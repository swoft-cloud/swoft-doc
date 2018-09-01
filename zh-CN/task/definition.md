# 定义任务

一个类就是一个任务组，类里面的每个方法，就是一个任务。


## 注解

**@Task**

- name 定义任务名称，缺省时类名，用于投递任务，且必须唯一
- coroutine 是否启动一个协程运行业务逻辑，缺省是false (由于Swoole的TaskWorker尚不支持运行协程代码，顾此选项目前仅做预留)


## 实例

```php
/**
 * @Task("demo")
 */
class DemoTask
{

    /**
     * Deliver coroutine task
     */
    public function coroutineJob(string $p1, string $p2): string
    {
        return sprintf('co-%s-%s', $p1, $p2);
    }

    /**
     * Deliver async task
     */
    public function asyncJob(string $p1, string $p2)
    {
        // Do anything you want.
        return sprintf('async-%s-%s', $p1, $p2);
    }
    
}
```

> 任务逻辑里面可以使用 Swoft 所有功能，唯一不一样的是，如果任务不是协程模式运行，所有I/O操作，框架底层会自动切换成传统的同步阻塞，但是使用方法是一样的。
