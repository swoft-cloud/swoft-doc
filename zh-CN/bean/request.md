# Request Bean

`score` 为 `request` 类型的 `bean`， 框架初始化的时候并不会`初始化`

而是在 `onRequest` 事件触发后 采用懒加载方式,

只有真正调用的时候才会被初始化，在当前请求中保持单例，请求结束后会被自动销毁。

## 使用

只能通过获取 `BeanFactory::getRequestBean` 获取
```php
$obj = BeanFactory::getRequestBean($name, (string) $tid);
```
- **name** requestBean 的名称/别名/类名
- **tid** 通常是与`是顶级协程ID`绑定。获取`顶级协程ID`，`Co::tid()`获取当前协程环境 `顶级协程ID`。

如果你需要获取当前请求`加载`了那些 `request bean` 。可以使用：
```php
$pool = BeanFactory::getContainer()->getRequestPool()
```
## 销毁

在**所有协程**执行完毕后，在`SwoftEvent::COROUTINE_COMPLETE`事件中，
会`自动销毁`与`顶级协程ID`绑定的`request bean`。

这时 `request bean`的生命周期也就结束了。
