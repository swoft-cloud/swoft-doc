# 使用 HTTP 客户端

## 创建一个请求
```php
use Swoft\HttpClient\Client;

$client = new Client([
    // 基础的 URI，将用于此对象后续发起的请求
    'base_uri' => 'http://www.swoft.org',
    // 请求的默认超时时间
    'timeout' => 2,
]);
```
Client 的构造方法接受一个 Options 数组，用于配置客户端，以为为各个参数的说明。

`base_uri` ( string | UriInterface ) 基础的 URI，将用户后续的请求，将与 request($uri) 的 $uri 合并成一个完整的 URI

`timeout` ( int | float ) 设置请求的超时时间，单位为秒

`adapter` ( string ) 设置指定的客户端适配器，可选参数包括 `co`, `curl`，`co` 指使用 Swoole 提供的协程 HTTP 客户端作为驱动，`curl` 指使用 CURL扩展 作为驱动

## 发送一个请求

```php
use Swoft\HttpClient\Client;

$client = new Client();
$response = $client->get('http://www.swoft.org')->getResponse();
$response = $client->post('http://www.swoft.org')->getResponse();
$response = $client->head('http://www.swoft.org')->getResponse();
$response = $client->options('http://www.swoft.org')->getResponse();
$response = $client->patch('http://www.swoft.org')->getResponse();
$response = $client->put('http://www.swoft.org')->getResponse();
$response = $client->delete('http://www.swoft.org')->getResponse();
```

所有请求都基于 `request(string $method, string|UriInterface $uri, array $options)` 方法，如有非预置自定义方法请求，可以使用此方法自行构造
```php
$method = 'GET';
/** @var Response $response */
$response = $client->request($method, '/', [
    'base_uri' => 'http://www.swoft.org',
])->getResponse();
```

## 并发请求
注意并发请求仅作用于`co`适配器下，若在`curl`驱动下此操作会退化为同步阻塞操作
```php
$request1 = $client->get('http://www.swoft.org');
$request2 = $client->get('http://www.swoft.org');
$request3 = $client->get('http://www.swoft.org');

$response1 = $request1->getResponse();
$response2 = $request2->getResponse();
$response3 = $request3->getResponse();
```

## HttpResult 对象
`\Swoft\HttpClient\HttpResult` 为请求后的返回结果，该结果不是请求返回的内容，注意调用后需统一调用 `getResponse()` 或 `getResult()` 方法获取`Response` 对象，框架将默认定义为延迟收包，调用这一方法才进行收包处理，当在协程驱动下，可实现 defer 特性和并发调用具体可参考 Swoole 关于并发操作的说明 https://wiki.swoole.com/wiki/page/p-coroutine_multi_call.html  
这里需要注意的是 `getResponse()` 或 `getResult()` 的返回值是不一样的，`getResponse()` 返回的是一个`Response` 对象，而 `getResult()` 返回的则是 `Response` 对象的 `Content` 属性，是一个字符串类型

## Response 对象
`\Swoft\Core\Response` 基于 `PSR-7` 实现，继承于 `Psr\Http\Message\ResponseInterface`，使用方法在此不做过多的阐述，可直接参考相关规范

## Raw 请求
通过对 `Options` 设置 `body` 参数设置发送请求的 Body，此参数仅允许字符串格式
```php
/** @var Response $response */
$response = $client->post('/', [
    'base_uri' => 'http://www.swoft.org',
    'body' => 'value',
])->getResponse();
```

## Form 请求
在表单提交下我们提供了`form_params`参数，该参数会自动在`Header`带上`application/x-www-form-urlencoded`的`Content-Type`

```php
/** @var Response $response */
$response = $client->post('/', [
    'base_uri' => 'http://www.swoft.org',
    'form_parasm' => [
        'key' => 'value',
    ],
])->getResponse();
```

## Json 请求
在表单提交下我们提供了`json`参数，该参数会自动在`Header`带上`application/json`的`Content-Type`，并格式化数组为`json`结构

```php
/** @var Response $response */
$response = $client->post('/', [
    'base_uri' => 'http://www.swoft.org',
    'json' => [
        'key' => 'value',
    ],
])->getResponse();
```