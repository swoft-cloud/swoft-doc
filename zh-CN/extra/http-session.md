# Http 会话管理

自 Swoft `v2.0.7` 官方提供了新的Http 会话管理组件。

> github - https://github.com/swoft-cloud/swoft-session

内置支持的驱动处理器：

- `file` 本机文件处理器
- `coFile` 本机文件处理器，但是使用swoole的协程方法读写内容
- `array` php内存驱动处理器，使用php array存储数据。_用于测试，数据仅在当前进程保存_
- `memTable` 使用swoole table 存储数据
- `redis` 使用redis存储数据，依赖swoft-redis组件

## 安装

http session 作为一个额外的功能组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/session
```

- 通过 composer.json 配置:

```json
    "swoft/session": "~2.0.0"
```

## 配置组件

### 启用组件

当你安装了 session 组件后，需要配置http 全局中间件来使用它(`app/bean.php`)。

```php
  'httpDispatcher'    => [
        // Add global http middleware
        'middlewares'      => [
            \Swoft\Http\Session\SessionMiddleware::class,
        ],
   ],
```

### 自定义配置

你可以自定义配置session组件，默认配置如下：

> 默认使用本地文件存储，存放于 `@runtime/sessions` 目录

```php
    'sessionManager' => [
        'class'   => SessionManager::class,
        'handler' => bean('sessionHandler'),
    ],
    'sessionHandler' => [
        'class'    => FileHandler::class,
        // For storage session files
        'savePath' => alias('@runtime/sessions')
    ],
```

### 配置redis驱动

```php
    'sessionHandler' => [
        'class'    => RedisHandler::class,
        // set redis pool
        'redis' => bean('redis.pool')
    ],
```

## 简单使用

### 设置值

```php
    HttpSession::current()->set(string $key, $value): viod
```

### 获取值

```php
    HttpSession::current()->get(string $key, $default)
```

### 检查是否存在

```php
    HttpSession::current()->has(string $key): bool
```

### 删除值

```php
    HttpSession::current()->delete(string $key): bool
```

## 使用示例

```php
<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
 
namespace App\Http\Controller;

use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Session\HttpSession;

/**
 * Class SessionController
 *
 * @Controller()
 */
class SessionController
{
    /**
     * @RequestMapping("/session")
     * @param Response $response
     *
     * @return Response
     */
    public function session(Response $response): Response
    {
        $sess  = HttpSession::current();
        $times = $sess->get('times', 0);
        $times++;
        $sess->set('times', $times);
        return $response->withData(['times' => $times]);
    }
    /**
     * @RequestMapping("all")
     *
     * @return array
     */
    public function all(): array
    {
        $sess = HttpSession::current();
        return $sess->toArray();
    }
    
    /**
     * @RequestMapping()
     * @param Response $response
     *
     * @return Response
     */
    public function set(Response $response): Response
    {
        $sess = HttpSession::current();
        $sess->set('testKey', 'test-value');
        $sess->set('testKey1', ['k' => 'v', 'v1', 3]);
        return $response->withData(['testKey', 'testKey1']);
    }
    
    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function get(): array
    {
        $sess = HttpSession::current();
        return ['get.testKey' => $sess->get('testKey')];
    }
    
    /**
     * @RequestMapping("del")
     *
     * @return array
     */
    public function del(): array
    {
        $sess = HttpSession::current();
        $result = $sess->del('testKey');
        return ['del.testKey' => $result];
    }
    
    /**
     * @RequestMapping()
     * @param Response $response
     *
     * @return Response
     */
    public function destroy(Response $response): Response
    {
        $sess = HttpSession::current();
        return $response->withData(['destroy' => $sess->destroy()]);
    }
    
    // ------------ flash session usage
    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function flash(): array
    {
        $sess = HttpSession::current();
        $sess->setFlash('flash1', 'test-value');
        return ['set.testKey' => 'test-value'];
    }
}
```

