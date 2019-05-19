# Request Bean

`score` 为 `request` 类型的 `bean`， 框架初始化的时候并不会`初始化`

而是在 `onRequest` 事件触发后 采用懒加载方式,

只有真正调用的时候才会被初始化，在当前请求中保持单例。

## 使用

你可以通过 `@Inject`注解注入
```php 
/**
 * @Inject("request-obj")
 *
 * @var RequestObj
 */
private $obj;
```
也可以通过获取
```php
$obj = BeanFactory::getRequestBean($name, $id);
```
- **name** requestBean 的名称/别名/类名
- **id** 通常是与`是顶级协程ID`绑定，当然你也可以指定协程绑定。通过 `Co::tid()`获取`顶级协程ID`，`Co::id()`获取当前协程环境 id。

> `@Inject` 注入的 request bean 默认是与`顶级协程ID`绑定的

如果你需要获取当前请求使用了那些 `request bean` 可以使用
```php
$pool = BeanFactory::getContainer()->getRequestPool()
```

## 销毁

在协程执行完毕的`SwoftEvent::COROUTINE_COMPLETE`事件中，
`自动销毁`当前协程`顶级协程ID`绑定的`request bean`。

这时 `request bean`的生命周期也就结束了。
