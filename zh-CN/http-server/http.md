Http Client 官方建议使用 saber 和 Guzzle ，不再重复造轮子。


## Saber

Swoole 官方封装的 Http client 库，已在多个大型项目中使用。

### 安装
```
composer require swlib/saber
```

### 使用

```php
SaberGM::get('http://httpbin.org/get');
SaberGM::delete('http://httpbin.org/delete');
SaberGM::post('http://httpbin.org/post', ['foo' => 'bar']);
SaberGM::put('http://httpbin.org/put', ['foo' => 'bar']);
SaberGM::patch('http://httpbin.org/patch', ['foo' => 'bar']);
```


## Guzzle

Guzzle 老牌 HTTP 封装库，很多依赖都是它封装的。

> swoole 版本必须 4.4 即以上 

### 安装
```
composer require guzzlehttp/guzzle
```

### 使用

```php
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');

echo $response->getStatusCode(); # 200
echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'