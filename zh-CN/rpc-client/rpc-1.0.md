# 1.0 RPC

如果系统之前使用的是 Swoft 1.0 RPC server，Swoft 2.0 定义了一种兼容 1.0 RPC 协议，使用很简单。

> Available: `>= v2.0.3`

## 配置

使用 2.0 框架中调用 1.0 RPC server 提供的服务，首先必须配置(app/bean.php) 1.0 RPC 协议


```php
return [
    // ...
    'user'             => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 8099,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
            'package_eof'     => "\r\n",
        ],
        'packet'  => bean('rpcClientSwoftPacketV1')
    ],
    
    // ...
];
```

- host/port 配置 1.0 地址和端口即可
- package_eof 必须配置数据包结尾符，1.0 包结尾符是 `\r\n`
- packet 必须配置使用 `bean('rpcClientSwoftPacketV1')` 1.0 打包器


## 使用

以上配置完成后，就可以直接使用了。这里直接以调用 Swoft 1.x 的 `App\Lib\DemoInterface` 为例:

```php
/**
 * Class RpcController
 *
 * @since 2.0
 *
 * @Controller()
 */
class RpcController
{
    /**
     * @Reference(pool="user.pool", version="0")
     *
     * @var DemoInterface
     */
    private $demoServcie;

    /**
     * @RequestMapping(route="swoftV1")
     *
     * @return array
     */
    public function swoftV1():array {
        return [$this->demoServcie->getUser('1')];
    }
}
```

- 调用 1.x RPC `version` 必须指定，因为 2.x 与 1.0 默认值不一样
- 不能调用 1.x 的 `deferXxxx` 方法 2.0 已经丢弃
- 2.x 里面调用的接口必须和 1.x 接口命名空间、类名以及方法名称参数完全一样。
