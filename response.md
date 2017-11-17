# 响应对象

Response 每一个请求是唯一的一个对象，请求处理完成后自动销毁。Response实现了PSR-7标准，可使用标准的方法进行操作。Response 对象获取一般有以下方式：

```php
// 全局获取
$response = App::getResponse();
$response = RequestContext::getResponse();

// Controller 内获取
$response = $this->response();
```

我们不建议在 Controller 内直接返回 Response 对象，而是由客户端来决定返回的格式，具体说明可阅读 [控制器](controller.md) 章节的 `Controller 返回的数据类型` 小节



