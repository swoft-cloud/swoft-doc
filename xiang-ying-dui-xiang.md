# 响应对应

Response每一个请求是唯一的一个对象，请求处理完成后自动销毁。Response可设置数据返回给用户的格式信息。Response对象获取一般有两种方式：

```php
$response = App::getResponse();
$response = RequestContext::getResponse();

// 设置httpCode
$response->setStatus(200);
// 设置返回内容content
$response->setResponseContent("xxx");
// 设置编码
$response->setCharset("xx");
// 设置格式
$response->setFormat(Response::FORMAT_JSON);
// 发送数据给用户
$response->send();
```



