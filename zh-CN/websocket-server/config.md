# websocket 配置

websocket 的host,port等配置是继承http server的。

## env配置

`.env` 新增配置项

```ini
# Swoole Settings
DISPATCH_MODE=2 # 注意!使用websocket 时分配模式不能为1和3
...
# WebSocket
WS_ENABLE_HTTP=true # 是否启用http处理
```

## server 配置

`config/server.php` 新增websocket配置项 `ws`

```php
'ws'  => [
    // enable handle http request ?
    'enable_http' => env('WS_ENABLE_HTTP', true),
    // other settings will extend the 'http' config
    // you can define separately to overwrite existing settings
],
```

> websocket 的其他配置是继承http服务的

## 扫描配置

> swoft `v1.0.6` 以上可以忽略此条，此版本以上已经是默认扫描app目录下所有文件了

在 `config/properties/app.php` 文件新增扫描配置，添加 `app/WebSocket` 目录

> 如果你的没有这项配置，请手动加上它

```php
'beanScan'     => [
    // ... ....
    'App\WebSocket',
],
```
