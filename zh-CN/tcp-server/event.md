# Tcp 事件通知

通常情况下，我们无需关心tcp server 相关的 connect close 事件。
但 swoft 内部都是监听并触发了框架内部定义的相关事件，你同样可以监听并处理一些逻辑。

## 事件列表

```php
<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

/**
 * Class TcpServerEvent
 *
 * @since 2.0
 */
final class TcpServerEvent
{
    /**
     * On connect
     */
    public const CONNECT = 'swoft.tcp.server.connect';

    /**
     * On connect error
     */
    public const CONNECT_ERROR = 'swoft.tcp.server.connect.error';

    /**
     * On receive
     */
    public const RECEIVE = 'swoft.tcp.server.receive';

    /**
     * On receive error
     */
    public const RECEIVE_ERROR = 'swoft.tcp.server.receive.error';

    /**
     * On close
     */
    public const CLOSE = 'swoft.tcp.server.close';

    /**
     * On close error
     */
    public const CLOSE_ERROR = 'swoft.tcp.server.close.error';
}
```

## 监听事件

跟其他事件一样，直接通过 `@Lisenter` 监听对应事件名，就可以处理相关逻辑了。

```php
<?php declare(strict_types=1);

namespace App\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Tcp\Server\TcpServerEvent;

/**
 * Class UserSavingListener
 *
 * @since 2.0
 *
 * @Listener(TcpServerEvent::CONNECT)
 */
class TcpConnectListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {

        /* @var \Swoole\Server $server */
        $server = $event->getTarget();
        
        var_dump(
          $event->getParam(0), // fd
          $event->getParam(1), // reactorId
        );
    }
}
```


