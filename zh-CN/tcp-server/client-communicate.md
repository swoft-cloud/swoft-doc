# 客户端通信

你可以直接使用 swoole 提供的 `Swoole\Coroutine\Client` 作为tcp客户端，快速的对接swoft的tcp sever。

为了分包和数据解析与tcp server保持一致，你需要依赖tcp协议包:

```bash
composer require swoft/tcp
```

## swoft 示例

```php
<?php declare(strict_types=1);

namespace App\Command;

use Swoft\Tcp\Protocol;
use Swoole\Coroutine\Client;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use const SWOOLE_SOCK_TCP;

// ...

    public function tcpTest(Input $input, Output $output): void
    {
        $proto = new Protocol();

        // If your tcp server use length check.
        // $proto->setOpenLengthCheck(true);

        var_dump($proto->getConfig());
        
        $host = '127.0.0.1';
        $port = 18309;
        
        $client = new Client(SWOOLE_SOCK_TCP);
        // Notice: config client
        $client->set($proto->getConfig());

        if (!$client->connect((string)$host, (int)$port, 5.0)) {
            $code = $client->errCode;
            /** @noinspection PhpComposerExtensionStubsInspection */
            $msg = socket_strerror($code);
            $output->error("Connect server failed. Error($code): $msg");
            return;
        }

        // Send message $msg . $proto->getPackageEOf()
        if (false === $client->send($proto->packBody($msg))) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $output->error('Send error - ' . socket_strerror($client->errCode));
            return;
        }

        // Recv response
        $res = $client->recv(2.0);
        if ($res === false) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $output->error('Recv error - ' . socket_strerror($client->errCode));
            return;
        }

        if ($res === '') {
            $output->info('Server closed connection');
            return;
        }

        // unpack response data
        [$head, $body] = $proto->unpackData($res);
        $output->prettyJSON($head);
        $output->writef('<yellow>server</yellow>> %s', $body);
    }
```

## 非swoft示例

> 注意：需要将tcp server 的 type 配置为 `json`

```php
<?php

const PKG_EOF = "\r\n\r\n";

function request(string $host, string $cmd, $data, $ext = []) {
    $fp = stream_socket_client($host, $errno, $errstr);
    if (!$fp) {
        throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
    }

    $req = [
        'cmd'  => $cmd,
        'data' => $data,
        'ext' => $ext,
    ];
    $data = json_encode($req) . PKG_EOF;
    fwrite($fp, $data);

    $result = '';
    while (!feof($fp)) {
        $tmp = stream_socket_recvfrom($fp, 1024);

        if ($pos = strpos($tmp, PKG_EOF)) {
            $result .= substr($tmp, 0, $pos);
            break;
        } else {
            $result .= $tmp;
        }
    }

    fclose($fp);
    return json_decode($result, true);
}

$ret = request('tcp://127.0.0.1:18309', 'echo', 'i an client');

var_dump($ret);
```
