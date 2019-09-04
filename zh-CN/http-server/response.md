# Http 响应对象

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

PSR-7 接口为响应对象提供了这些方法:

- `withStatus($code, $reasonPhrase = '')`

> 更多请参考 PSR-7 和 查看 [swoft/http-message](https://github.com/swoft-cloud/swoft-http-message) 中具体的实现类

## 如何获取

- 通过控制器方法参数注入 `(Response $response)`
- 通过请求上下文获取 `Swoft\Context\Context::mustGet()->getResponse()`

## 设置状态码

```php
$response = \Swoft\Context\Context::mustGet()->Response();
return $response->withStatus(404);
```

## 输出字符串内容

```php
return $response->withContent("Hello Swoft2.0");
```

## 输出数组

```php
$data = ['name'=>'Swoft2.0'];
$response->withData($data);
```

## 输出头信息

```php
return $response->withHeader("name","Swoft2.0");
```

## 重定向

```php
return $response->redirect("http://www.swoft.org",302);
```

## 文件下载

```php
return $response->file(\alias('@runtime/1.zip'), "application/octet-stream");
```

## 设置Cookies

```php
$response = $response->withCookie(’name', 'value');

$response = $response->withCookie(’name', [
    'value'    => 'value3',
    'httpOnly' => true
]);
```

设置多个：

```php
$cookies = [
    'key1' => 'value1',
    'key2' => [
        'value' => 'value2',
    ],
    'key3' => [
        'value'    => 'value3',
        'httpOnly' => true
    ],
];

$response = $response->withCookies($cookies);
```

