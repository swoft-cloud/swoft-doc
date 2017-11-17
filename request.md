# 请求对象

Request每一个请求是唯一的一个对象，请求处理完成后自动销毁。Request我们实现了PSR-7标准，对应里面可以获取请求参数、URI、Header等。Request对象获取一般有以下方式：

```php
// app里面简写获取
$request = App::getRequest();

// 请求上下文中获取
$request = RequestContext::getRequest();

// Controller 中获取
$request = $this->request();

/***************** Request对象常用操作  ******************/
// 获取GET参数
$request->query();
// 获取POST参数
$request->post();
// 获取GET和POST所有参数
$request->input();
// 获取name GET或POST
$request->input('name', 'defaultName');
// 获取header
$request->header('key', 'default');
// 获取URI
$request->getUri()->getPath();
// 获取请求方法 get或post...
$request->getMethod();
// 获取请求content内容长度
$request->getBody()->getSize();
// 获取contentType类型
$request->getHeaderLine('Content-Type');
```



