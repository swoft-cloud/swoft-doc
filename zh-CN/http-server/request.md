# Http 请求对象

Swoft 的请求与响应实现于 PSR 7 规范。请求与响应对象存在于每次 HTTP 请求。

- 请求对象 `Request` 为 `Swoft\Http\Message\Request`
- 响应对象 `Response` 为 `Swoft\Http\Message\Response`

<p class="tip">注意!<br>
根据PSR-7对象的不可变性(immutable)，所有的 with* 方法都是克隆对象然后返回，必须接收新对象来做进一步处理，或使用链式调用
</p>

## 基本方法

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

> 更多请参考 PSR-7 和 查看 [swoft/http-message](https://github.com/swoft-cloud/swoft-http-message) 中具体的实现类

## 如何获取

- 通过控制器方法参数注入 `(Request $request)`
- 通过请求上下文获取 `Swoft\Context\Context::mustGet()->getRequest()`

## 请求动作方法

```php
$request = \Swoft\Context\Context::mustGet()->getRequest();
$method = $request->getMethod();
```

## 请求的URI

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

## 请求 Headers

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

## 请求数据获取

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

> 提示： 无需关系请求的数据格式，`json` `xml` 请求都会自动解析为php的数组数据。都可以通过 `$request->post()` 获取。

### GET & POST 数据

```php
$data = $request->input();
$some = $request->input('key', 'default value')
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

### 获取上传文件

```php
$file = $request->getUploadedFiles();
```
获取的结果是一维数组或者二位数组，数据结构如下。
若表单中上传的是单文件则返回的是一个一维数组，数组内容是 `Swoft\Http\Message\Upload\UploadedFile` 文件对象，例如文件字段名为 `file` 则数据结构为
```text
array(1) {
  ["file"]=>
  object(Swoft\Http\Message\Upload\UploadedFile)#6510 (7) {
    ["size":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    int(1319)
    ["errorCode":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    int(0)
    ["file":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    string(25) "/tmp/swoole.upfile.f7p2EL"
    ["clientFilename":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    string(6) "at.png"
    ["clientMediaType":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    string(9) "image/png"
    ["moved":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    NULL
    ["path":"Swoft\Http\Message\Upload\UploadedFile":private]=>
    NULL
  }
}
```
若表单中是一个字段数组上传多个文件如 `file[]` 则返回的是一个二维数组，数组内容依然是 `Swoft\Http\Message\Upload\UploadedFile` 文件对象，数据结构如下
```text
array(1) {
  ["file"]=>
  array(2) {
    [0]=>
    object(Swoft\Http\Message\Upload\UploadedFile)#6516 (7) {
      ["size":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      int(1319)
      ["errorCode":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      int(0)
      ["file":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(25) "/tmp/swoole.upfile.TVKdOS"
      ["clientFilename":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(6) "at.png"
      ["clientMediaType":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(9) "image/png"
      ["moved":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      NULL
      ["path":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      NULL
    }
    [1]=>
    object(Swoft\Http\Message\Upload\UploadedFile)#6510 (7) {
      ["size":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      int(5489)
      ["errorCode":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      int(0)
      ["file":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(25) "/tmp/swoole.upfile.XS2vQg"
      ["clientFilename":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(8) "deal.png"
      ["clientMediaType":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      string(9) "image/png"
      ["moved":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      NULL
      ["path":"Swoft\Http\Message\Upload\UploadedFile":private]=>
      NULL
    }
  }
}
```
## 文件操作方法

- `moveTo()` 将上传的文件移动到新位置。
- `getSize()` 获取文件大小，单位 `byte`。
- `getError()` 获取上传文件相关的错误信息，若无错将必须返回`UPLOAD_ERR_OK` 常量，若又错误将返回`UPLOAD_ERR_XXX` 相关常量。
- `getClientFilename()` 获取文件上传时客户端本地的文件名，不要相信此方法返回的值。客户端可能会发送*恶意虚假文件名*，意图破坏或破解您的应用程序。
- `getClientMediaType()` 获取客户端中文件的 `MediaType` 类型，不要相信此方法返回的值。客户端可能会发送*恶意虚假文件名*，意图破坏或破解您的应用程序。

## 一些辅助方法

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

