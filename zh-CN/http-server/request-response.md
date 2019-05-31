# 请求与响应

Swoft 的请求与响应实现于 [PSR 7](https://github.com/php-fig/http-message)

请求与响应对象存在于每次 HTTP 请求，这里指的 `Request` 为 `Swoft\Http\Message\Server\Request`，`Response` 为 `Swoft\Http\Message\Server\Response`。

## PSR-7

  <p class="tip"><strong>注意!</strong> 根据PSR-7对象的不可变性(immutable)，所有的 <code>with*</code> 方法都是克隆对象然后返回，必须接收新对象来做进一步处理，或使用链式调用</p>

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

<p class="tip">
   <strong>Tips</strong> 可通过使用链式调用的写法使代码变得更简洁
</p>

## 请求对象

### 如何获取

- 通过 Action 参数注入
- 通过请求上下文获取 `Swoft\Core\RequestContext::getRequest()`
- 通过全局函数 `request()` 获取

### 请求动作方法

```php
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
    echo $name . ": " . implode(", ", $values);
}
```

#### 指定的 Header

- 返回值是array

```php
$headerValueArray = $request->getHeader('Accept');
```

- 返回值是字符串

```php
$headerValueString = $request->getHeaderLine('Accept');
```

#### 一些辅助方法

- XHR 

```php
if ($request->isAjax()) {
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

### 额外的方法

- 获取 Swoole 的 Request 对象

```php
$swooleRequest = $request->getSwooleRequest();
```

## 响应对象

### 额外的方法

- 获取 Swoole 的 Response 对象

```php
$swooleResponse = $response->getSwooleResponse();
```
