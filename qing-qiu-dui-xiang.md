# 请求对象

Request每一个请求是唯一的一个对象，请求处理完成后自动销毁。Request对应里面可以获取请求参数、URI、Header等。Request对象获取一般有两种方式：

```php
// app里面简写获取
$request = App::getRequest();

// 请求上下文中获取
$request = RequestContext::getRequest();

/***************** Request对象常用操作  ******************/
// 获取GET参数
$request->getGetParameters();
// 获取POST参数
$request->getPostParameters();
// 获取GET和POST所有参数
$request->getParameters();
// 获取name GET或POST
$request->getParameter('name', 'defaultName');
// 获取header
$request->getHeader('key', 'default');
// 获取URI
$request->getRequestUri();
// 获取请求方法 get或post...
$request->getMethod();
// 获取请求content内容长度
$request->getContentLength();
// 获取contentType类型
$request->getContentType();
// 获取内容char Encode
$request->getCharacterEncoding();
```



