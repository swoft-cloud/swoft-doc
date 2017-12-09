# HTTP中间件

用户可以自定义一些中间，满足不同的业务场景。HTTP中可以通过注解和配置两种方式使用已经定义好的中间。

## 注解使用

### @Middleware

- @Middleware，可以指定一个中间件名称
- 多个@Middleware中间件，按照配置顺序执行
- 如果定义在控制器，整个作用于整个控制器里面action
- 如果定义在action，仅仅作用于当前action

### @Middlewares

- @Middlewares，通过一个@Middleware数组，定义一个组中间件
- @Middlewares，里面多个@Middleware中间件，按照配置顺序执行
- 如果定义在控制器，整个作用于整个控制器里面action
- 如果定义在action，仅仅作用于当前action

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

### 配置使用

用户也可以通过配置(app/config/beans/base.php)使用注解，执行顺序按照配置数组顺序。

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