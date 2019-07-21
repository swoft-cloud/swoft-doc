# 与客户端通信


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
        
        $client = new Client(SWOOLE_SOCK_TCP);
        // Notice: config client
        $client->set($proto->getConfig());

        if (!$client->connect((string)$host, (int)$port, 5.0)) {
            $code = $client->errCode;
            /** @noinspection PhpComposerExtensionStubsInspection */
            $msg = socket_strerror($code);
            var_dump("Connect server failed. Error($code): $msg");
            return;
        }

        // Send message $msg . $proto->getPackageEOf()
        if (false === $client->send($proto->packBody($msg))) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $output->error('Send error - ' . socket_strerror($client->errCode));

            if ($this->quickConfirm($input, 'Reconnect', true)) {
                $client->close();
                goto CONNCET;
            }

            $output->colored('GoodBye!');
            break;
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