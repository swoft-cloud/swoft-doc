# 服务降级

服务降级目前初步做的比较简单，比如内部服务调用失败后，为不影响正常请求，可以实现 Fallback，实现对服务的降级。Fallback 定义有目前有三种形式。

1. 全局函数
2. 匿名函数
3. 可以调用的 Callback

```php
function method()
{
    // ..
}
// 全局函数
$result = Service::call("user", 'User::getUserInfo', [2,6,8], 'method');

// 匿名函数
$result = Service::call("user", 'User::getUserInfo', [2,6,8], function(){
    // ...
});

// callback
$result = Service::call("user", 'User::getUserInfo', [2,6,8], [Object, 'method']);
```



