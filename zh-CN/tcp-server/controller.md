# TCP控制器

与http server类似，tcp server中也使用对应的控制器来处理系统分发的数据请求。

## 注解

tcp server新增两个注解 `@TcpControler` 和 `@TcpMapping`，由他们定义tcp控制器和处理方法。

### TcpControler

类注解 `@TcpControler` 标记当前类是一个 Tcp 控制器。

- 注解类： `Swoft\Tcp\Server\Annotation\Mapping\TcpController`
- 作用范围： `CLASS`
- 拥有属性：
    + `prefix` _string_ 数据路由前缀，为空自动解析类名称为前缀。

### TcpMapping

方法注解 `@TcpMapping` 标记具体的数据处理方法，类似于http控制器里的action。

- 注解类： `Swoft\Tcp\Server\Annotation\Mapping\TcpMapping`
- 作用范围： `METHOD`
- 拥有属性：
    + `route` _string_ 命令名称，为空自动使用方法名称。
    + `root` _bool_ 命令名称是否是顶级命令。默认 `false`

### 提示

- 自动解析 `TcpControler` 的前缀时，会自动尝试去除 `Controler` 部分。eg: `DemoController` 得到 `demo`
- 通常，完整的tcp命令是 上面的 `preifx` 和 `route` 由点拼接而成 `PREFIX.ROUTE`。eg: `demo.index`
- 当 `TcpMapping.root` 为 `true` 时，完整命令直接是 `TcpMapping.route`

## 编写控制器

```php
<?php declare(strict_types=1);

namespace App\Tcp\Controller;

use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Response;

/**
 * Class DemoController
 *
 * @TcpController()
 */
class DemoController
{
    /**
     * @TcpMapping("list", root=true)
     * @param Response $response
     */
    public function list(Response $response): void
    {
        $response->setData('[list]allow command: list, echo, demo.echo');
    }

    /**
     * @TcpMapping("echo")
     * @param Request  $request
     * @param Response $response
     */
    public function index(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();

        $response->setData('[demo.echo]hi, we received your message: ' . $str);
    }

    /**
     * @TcpMapping("strrev", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function strRev(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();

        $response->setData(\strrev($str));
    }

    /**
     * @TcpMapping("echo", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function echo(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();

        $response->setData('[echo]hi, we received your message: ' . $str);
    }
}
```

好了，服务端代码已经编写好了。这里我们使用默认的配置 `EOF` 分包方式，数据协议格式也使用默认的 `SimpleTokenPacker::TYPE`。

重新启动我们的 tcp server `php bin/swoft tcp:start`，接下来一篇讲述如何与我们的tcp server进行通信交互。
