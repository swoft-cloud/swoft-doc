# HTTP服务

系统提供HttpClient来实现HTTP调用，目前有两种方式，直接调用和延迟收包调用，延迟收包，一般用于并发调用。

```php
// 直接调用
$requestData = [
    'name' => 'boy',
    'desc' => 'php'
];
        
$result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
$result = $result;

// 延迟调用方式实现两个请求并发调用
$ret = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
$ret2 = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
$defRet1 = $ret->getResult();
$defRet2 = $ret->getResult();
```



