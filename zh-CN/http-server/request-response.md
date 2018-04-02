# 请求与响应

swoft 的请求与响应实现于 [PSR 7](https://github.com/php-fig/http-message)

请求与响应对象存在于每次http请求。

## PSR7

> 注意：所有的 `with*` 方法都是克隆对象然后返回，必须接收新对象来做进一步处理

PSR 7接口为请求和响应对象提供了这些公共方法:

- `withProtocolVersion($version)`
- `withHeader($name, $value)`
- `withAddedHeader($name, $value)`
- `withoutHeader($name)`
- `withBody(StreamInterface $body)`

PSR 7接口为请求对象提供了这些方法:

- `withMethod(string $method)`
- `withUri(UriInterface $uri, $preserveHost = false)`
- `withCookieParams(array $cookies)`
- `withQueryParams(array $query)`
- `withUploadedFiles(array $uploadedFiles)`
- `withParsedBody($data)`
- `withAttribute($name, $value)`
- `withoutAttribute($name)`

PSR 7接口为响应对象提供了这些方法:

- `withStatus($code, $reasonPhrase = '')`

> 更多请参考 PSR7 和 查看 `swoft/http-message` 中具体的实现类

## 请求对象

### 如何获取

- 通过action 参数注入
- 上下文获取 `RequestContext::getRequest()`
- 全局函数 `request()`

### 请求动作方法

```php
$method = $request->getMethod();
```

### 请求的URI

每个HTTP请求都有一个URI标识所请求的应用程序资源。HTTP请求URI有几个部分:

- Scheme (e.g. `http` or `https`)
- Host (e.g. `example.com`)
- Port (e.g. `80` or `443`)
- Path (e.g. `/users/1`)
- Query `string` (e.g. `sort=created&dir=asc`)

你可以通过请求对象的 `getUri()` 方法获取 PSR 7 [URI对象](http://www.php-fig.org/psr/psr-7/#3-5-psr-http-message-uriinterface):

```php
$uri = $request->getUri();
```

PSR 7请求对象的URI本身就是一个对象,它提供了下列方法检查HTTP请求的URL部分:

- `getScheme()`
- `getAuthority()`
- `getUserInfo()`
- `getHost()`
- `getPort()`
- `getPath()`
- `getQuery()` (e.g. `a=1&b=2`)
- `getFragment()`

### 请求Headers

#### 全部的headers

```php
$headers = $request->getHeaders();

foreach ($headers as $name => $values) {
    echo $name . ": " . implode(", ", $values);
}
```

#### 指定的header

- 值是array

```php
$headerValueArray = $request->getHeader('Accept');
```

- 值是字符串

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

- Content Type

```php
$contentType = $request->getContentType();
```

### 请求数据获取

## 响应对象
