# Swoft Whoops

`swoft/whoops` 是对 `filp/whoops` 在swoft中使用的简单封装，用于渲染并显示更加利于阅读的异常错误信息。

![swoft-whoops](https://raw.githubusercontent.com/swoft-cloud/swoft-doc/2.x/zh-CN/image/extra/swoft-whoops.jpg)

## Github

- https://github.com/swoft-cloud/swoft-whoops.git

## 安装

`swoft/whoops` 作为一个额外的扩展组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/whoops
```

- 通过 `composer.json` 配置:

```json
    "swoft/whoops": "~2.0.0"
```

## 使用

### 作为中间件使用

使用 `Swoft\Whoops\WhoopsMiddleware` 作为一个全局的Http中间件(`app/bean.php`):

```php
    'httpDispatcher'   => [
        // Add global http middleware
        'middlewares' => [
            // Notice: Please add to the first place
            \Swoft\Whoops\WhoopsMiddleware::class,
        ],
    ],
```

> 注意: 请将 `Swoft\Whoops\WhoopsMiddleware` 加到第一个位置

### 异常处理里使用

我们稍微调整一下默认的http异常处理类(`App\Exception\Handler\HttpExceptionHandler`):

```php
<?php declare(strict_types=1);

namespace App\Exception\Handler;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Swoft\Log\Helper\CLog;
use Swoft\Whoops\WhoopsHandler;
use Throwable;
use function bean;
use function context;
use const APP_DEBUG;

/**
 * Class HttpExceptionHandler
 *
 * @ExceptionHandler(\Throwable::class)
 */
class HttpExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(Throwable $e, Response $response): Response
    {
        $request = context()->getRequest();
        if ($request->getUriPath() === '/favicon.ico') {
            return $response->withStatus(404);
        }
        
        // Log
        CLog::error($e->getMessage());
        
        // Debug is false
        if (!APP_DEBUG) {
            return $response
                ->withStatus(500)
                ->withContent($e->getMessage());
        }
        
        // Debug is true
        $whoops  = bean(WhoopsHandler::class);
        $content = $whoops->run($e, $request);
        return $response->withContent($content);
    }
}
```

现在我们重启 http server，当出现异常时就可以看到由 `whoops` 渲染的错误信息页面了。

## 参与贡献

欢迎参与贡献，您可以

- fork 我们的开发仓库 [swoft/whoops](https://github.com/swoft-cloud/swoft-whoops)
- 修改代码然后发起 PR
- 关于发起PR的[注意事项](https://github.com/swoft-cloud/swoft/issues/829)
