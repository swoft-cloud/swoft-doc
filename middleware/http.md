# HTTP 中间件

用户可以自定义一些中间件，满足不同的业务场景。HTTP 中可以通过注解方式使用已经定义好的中间件，全局中间件需在配置中定义。

## 注解使用

### @Middleware

- `@Middleware`，可以指定一个中间件名称
- 如果定义在控制器，整个作用于整个控制器里面 Action
- 如果定义在 Action，仅仅作用于当前 Action

### @Middlewares

- `@Middlewares`，通过一个`@Middleware`数组，定义一个组中间件
- `@Middlewares`，里面多个`@Middleware`中间件，按照配置顺序执行
- 如果定义在控制器，整个作用于整个控制器里面的所有 Action
- 如果定义在 Action，仅仅作用于当前 Action

### 使用实例

```php
/**
 * @Controller("md")
 *
 * @Middleware(class=ControlerTestMiddleware::class)
 * @Middlewares({
 *     @Middleware(ControlerSubMiddleware::class)
 * })
 *
 * @uses      MiddlewareController
 * @version   2017年11月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MiddlewareController
{
    /**
     * @RequestMapping(route="caa")
     *
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     * @Middleware(SubMiddleware::class)
     */
    public function controllerAndAction()
    {
        return ['middleware'];
    }

    /**
     * @RequestMapping(route="caa2")
     *
     * @Middleware(SubMiddleware::class)
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     */
    public function controllerAndAction2()
    {
        return ['middleware2'];
    }
}
```

### 全局中间件配置

通过配置 `app/config/beans/base.php` 注册全局中间件，执行顺序按照配置的数组顺序，全局中间件将会在每一次 HTTP 请求中被执行。

```php
    return [
        // ...
        'dispatcherServer' => [
            'class' => \Swoft\Web\DispatcherServer::class,
            'middlewares' => [
                \App\Middlewares\ControlerTestMiddleware::class,
                \App\Middlewares\SubMiddleware::class
            ]
        ],
        // ...    
    ];
```
