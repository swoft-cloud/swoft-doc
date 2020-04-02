# 如何使用

## 注解

### @Reference

- pool 指定使用那个服务的连接池(使用那个服务)
- version 指定服务的版本

## 代码示例

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
     * @Reference(pool="user.pool")
     *
     * @var UserInterface
     */
    private $userService;

    /**
     * @Reference(pool="user.pool", version="1.2")
     *
     * @var UserInterface
     */
    private $userService2;

    /**
     * @RequestMapping("getList")
     *
     * @return array
     */
    public function getList(): array
    {
        $result  = $this->userService->getList(12, 'type');
        $result2 = $this->userService2->getList(12, 'type');

        return [$result, $result2];
    }

    /**
     * @RequestMapping("returnBool")
     *
     * @return array
     */
    public function returnBool(): array
    {
        $result = $this->userService->delete(12);

        if (is_bool($result)) {
            return ['bool'];
        }

        return ['notBool'];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function bigString(): array
    {
        $string = $this->userService->getBigContent();

        return ['string'];
    }
}
```

> 提示：接口只是定义了方法，注入也是 refer 的接口类。但是使用是要用真实类的实例。所以swoft会自动生成对应的类实现到临时目录。

## 非 Swoft 框架调用

默认消息协议是 json-rpc， 所以我们按照这个格式就可以了，需要注意的是，默认消息协议是以 `\r\n\r\n` 结尾的。

这里 `method` 的格式为 `"{version}::{class_name}::{method_name}"`

```json
{
    "jsonrpc": "2.0",
    "method": "{version}::{class_name}::{method_name}",
    "params": [],
    "id": "",
    "ext": []
}
```

### 代码示例

如果使用默认消息协议，可以按照如下方式进行封装

```php
<?php

const RPC_EOL = "\r\n\r\n";

function request($host, $class, $method, $param, $version = '1.0', $ext = []) {
    $fp = stream_socket_client($host, $errno, $errstr);
    if (!$fp) {
        throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
    }

    $req = [
        "jsonrpc" => '2.0',
        "method" => sprintf("%s::%s::%s", $version, $class, $method),
        'params' => $param,
        'id' => '',
        'ext' => $ext,
    ];
    $data = json_encode($req) . RPC_EOL;
    fwrite($fp, $data);

    $result = '';
    while (!feof($fp)) {
        $tmp = stream_socket_recvfrom($fp, 1024);
        $pos = strpos($tmp, RPC_EOL);

        if ($pos !== false) {
            $result .= substr($tmp, 0, $pos);
            break;
        } else {
            $result .= $tmp;
        }
    }

    fclose($fp);
    return json_decode($result, true);
}

$ret = request('tcp://127.0.0.1:18307', \App\Rpc\Lib\UserInterface::class, 'getList',  [1, 2], "1.0");

var_dump($ret);
```
