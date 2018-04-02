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


## 响应对象
