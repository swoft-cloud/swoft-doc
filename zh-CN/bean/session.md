# session

## 简介
`session bean` 和 `request bean` 都是用的懒加载方式，只有在调用  `bean`的时候才会被初始化, 在而且在当前会话 保持`单例`

`session bean` 类型适合长连接使用，比如`webSocket`就比较适合它, 它的实现方式和 `request bean` 基本一样。

## 使用

你可以通过 `@Inject`注解注入
```php 
/**
 * @Inject()
 *
 * @var SessionObj
 */
private $sessionObj;
```
也可以通过获取
```php
$obj = BeanFactory::getSessionBean($name, $id);
```
- **name** sessionBean 的名称/别名/类名
- **id** 通常是与`是顶级协程ID`绑定，当然你也可以指定协程绑定。通过 `Co::tid()`获取`顶级协程ID`，`Co::id()`获取当前协程环境 id。

> `@Inject` 注入的 request bean 默认是与`顶级协程ID`绑定的


如果你需要获取当前会话使用了那些 `session bean` 可以使用获取
```php
$pool = BeanFactory::getContainer()->getSessionPool()
```

## 销毁
 
 score 为`session`  的`bean`会在`BeanEvent::DESTROY_SESSION`事件中被销毁。
 
 这时 `session bean`的生命周期也就结束了。