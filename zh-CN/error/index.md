# 错误处理

swoft 提供了完善的异常与错误处理机制，与通常fpm下情况不同，swoft里将错误分为了不同的场景类型。

## 安装

```bash
composer require swoft/error
```

## 错误场景

场景类型的划分：主要是根据swoole的回调事件范围来划分。

> 在不同的场景里，即使是同一个地方抛出的异常或错误，处理方式可能也是不同的。

比如：db里抛出了一个 `DbException`，在 http 运行场景里你需要处理后返回一个 Reponse 对象。而在cli等环境里，你可以只处理无需返回任何结果

## 错误场景类型

在 `Swoft\Error\ErrorType` 定义了swoft划分的场景类型。

```php
<?php declare(strict_types=1);

namespace Swoft\Error;

/**
 * Class ErrorType
 *
 * @since 2.0
 */
final class ErrorType
{
    // Console application
    public const CLI  = 2;
    
    public const RPC  = 3;
    public const UDP  = 4;
    public const SOCK = 7;
    public const TASK = 8;

    public const WORKER = 9;

    // HTTP server
    public const HTTP = 16;

    // WebSocket server
    public const WS_HS  = 21;
    public const WS_OPN = 22;
    public const WS_MSG = 23;
    public const WS_CLS = 24;

    // Tcp server
    public const TCP_CNT = 31;
    public const TCP_RCV = 32;
    public const TCP_CLS = 33;

    public const SYS = 85;

    // Default error type
    public const DEF     = 90;
    public const DEFAULT = 90;
}
```

现在支持错误处理的有：

- console 应用场景
- http server 应用场景
- rpc server 应用场景
- tcp server 应用场景
- websocket server 应用场景

异常处理的具体使用，请继续查看下一篇文档。

## Git仓库

- Github https://github.com/swoft-cloud/swoft-error

## 参与贡献

欢迎参与贡献，您可以

- fork 我们的开发仓库 [swoft/component](https://github.com/swoft-cloud/swoft-component)
- 修改代码然后发起 PR
- 关于发起PR的[注意事项](https://github.com/swoft-cloud/swoft/issues/829)
