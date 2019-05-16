# 请求与响应

Swoft 的请求与响应实现于 PSR 7

请求与响应对象存在于每次 HTTP 请求，这里指的 `Request` 为 `Swoft\Http\Message\Request`，`Response` 为 `Swoft\Http\Message\Response`。

注意!
根据PSR-7对象的不可变性(immutable)，所有的 with* 方法都是克隆对象然后返回，必须接收新对象来做进一步处理，或使用链式调用

### 基本方法

PSR-7 接口为请求和响应对象提供了这些公共方法:

- `withProtocolVersion($version)`
- `withHeader($name, $value)`
- `withAddedHeader($name, $value)`
- `withoutHeader($name)`
- `withBody(StreamInterface $body)`

PSR-7 接口为请求对象提供了这些方法:

- `withMethod(string $method)`
- `withUri(UriInterface $uri, $preserveHost = false)`
- `withCookieParams(array $cookies)`
- `withQueryParams(array $query)`
- `withUploadedFiles(array $uploadedFiles)`
- `withParsedBody($data)`
- `withAttribute($name, $value)`
- `withoutAttribute($name)`

PSR-7 接口为响应对象提供了这些方法:

- `withStatus($code, $reasonPhrase = '')`

> 更多请参考 PSR-7 和 查看 `swoft/http-message` 中具体的实现类

## 请求对象

### 如何获取

- 通过控制器 Action 参数注入
- 通过请求上下文获取 `Swoft\Context\Context::mustGet()->getRequest()`

### 请求动作方法
```php
$request = \Swoft\Context\Context::mustGet()->getRequest();
$method = $request->getMethod();
```

### 请求的URI

每个 HTTP 请求都有一个URI标识所请求的应用程序资源。HTTP 请求 URI 有几个部分:

- Scheme (e.g. `http` or `https`)
- Host (e.g. `example.com`)
- Port (e.g. `80` or `443`)
- Path (e.g. `/users/1`)
- Query `string` (e.g. `sort=created&dir=asc`)

你可以通过请求对象的 `getUri()` 方法获取 PSR-7 [URI对象](http://www.php-fig.org/psr/psr-7/#3-5-psr-http-message-uriinterface):

```php
$uri = $request->getUri();
```

PSR-7 请求对象的 URI 本身就是一个对象,它提供了下列方法检查 HTTP 请求的 URL 部分:

- `getScheme()`
- `getAuthority()`
- `getUserInfo()`
- `getHost()`
- `getPort()`
- `getPath()`
- `getQuery()` (e.g. `a=1&b=2`)
- `getFragment()`

### 请求 Headers

#### 全部的 Headers

```php
$headers = $request->getHeaders();

foreach ($headers as $name => $values) {
    echo $name . ": " . implode(", ", $values).PHP_EOL;
}
```

#### 指定的 Header

- 返回值是array

```php
$headerValueArray = $request->getHeader('host');
print_r($headerValueArray);
```

- 返回值是字符串

```php
$host = $request->getHeaderLine("host");
print_r($host);
```

#### 一些辅助方法

- XHR 

```php
if ($request->isAjax()) {
    // Do something
}
if ($request->isXmlHttpRequest()) {
    // Do something
}
```

- GET 

```php
if ($request->isGet()) {
    // Do something
}
```

- POST

```php
if ($request->isPost()) {
    // Do something
}
```

- PUT

```php
if ($request->isPut()) {
    // Do something
}
```

- DELETE

```php
if ($request->isDelete()) {
    // Do something
}
```

- PATCH

```php
if ($request->isPatch()) {
    // Do something
}
```

- Content-Type

```php
$contentType = $request->getContentType();
```

### 请求数据获取

### GET 数据

```php
$data = $request->query();
$some = $request->query('key', 'default value')
$data = $request->get();
$some = $request->get('key','default value');
```

### POST 数据

```php
$data = $request->post();
$some = $request->post('key', 'default value')
```

### GET & POST 数据

```php
$data = $request->input();
$some = $request->input('key', 'default value')
```

### JSON 数据

仅当 `Content-Type` 为 `application/json` 时有效

```php
$data = $request->json();
$some = $request->json('key', 'default value')
```

### RAW 数据

```php
$data = $request->raw();
```

### SERVER 数据

```php
$data = $request->getServerParams();
$some = $request->server('key', 'default value')
```

## 响应对象

### 如何获取

- 通过控制器 Action 参数注入
- 通过请求上下文获取 `Swoft\Context\Context::mustGet()->getResponse()`

### 常用方法

### 输出状态码

```php
$response = \Swoft\Context\Context::mustGet()->Response();
return $response->withStatus(404);
```

### 输出字符串内容

```php
return $response->withContent("Hello Swoft2.0");
```

### 输出数组

```php
$data = ['name'=>'Swoft2.0'];
$response->withData($data);
```

### 输出头信息

```php
return $response->withHeader("name","Swoft2.0");
```

### 重定向

```php
return $response->redirect("http://www.swoft.org",302);
```

### 文件下载

```php
return $response->file(\alias('@runtime/1.zip'),"application/octet-stream");
```
