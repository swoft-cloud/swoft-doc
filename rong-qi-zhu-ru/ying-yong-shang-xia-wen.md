# 请求上下文

RequestContext一般用户获取当前请求对应的Response和Response及数据。这些都是当前请求有效，请求执行完成后，自动销毁。

```php
// 获取Request对象
RequestContext::getRequest();

// 获取Respone对象
RequestContext::getResponse();

// 获取当前请求所有data
RequestContext::getContextData();

// 设置当前请求data某一个KEY值
RequestContext::setContextDataByKey("key", 'val');

// 获取当前请求data某一个KEY值
RequestContext::getContextDataByKey("key", 'default');
```



