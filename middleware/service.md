# Service 中间件

用户可以自定义一些中间件，满足不同的业务场景。Service 中可以通过注解方式使用已经定义好的中间件，全局中间件需在配置中定义。

## 注解使用

### @Middleware

- `@Middleware`，可以指定一个中间件名称
- 如果定义在 Service 类上，作用于整个类的所有方法函数
- 如果定义在一个函数上面，仅仅作用于当前方法函数

### @Middlewares

- `@Middlewares`，通过一个`@Middleware`数组，定义一个组中间件
- `@Middlewares`，里面多个`@Middleware`中间件，按照配置顺序执行
- 如果定义在 Service 类上，作用于整个类的所有方法函数
- 如果定义在一个函数上面，仅仅作用于当前方法函数

### 使用实例

```php
/**
 * @Service("Md")
 * @Middlewares({
 *     @Middleware(ServiceSubMiddleware::class)
 * })
 * @uses      MiddlewareService
 * @version   2017年12月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MiddlewareService
{
    /**
     * @Mapping("pm")
     *
     * @return array
     */
    public function parentMiddleware()
    {
        return ['pm'];
    }

    /**
     * @Mapping("fm")
     *
     * @Middleware(class=ServiceMiddleware::class)
     * @return array
     */
    public function funcMiddleware()
    {
        return ['fm'];
    }
}
```

### 全局中间件配置

通过配置 `app/config/beans/base.php` 注册全局中间件，执行顺序按照配置的数组顺序，全局中间件将会在每一次 RPC 调用中被执行。

```php
    return [
        // ...
        'dispatcherService' => [
            'class' => \Swoft\Service\DispatcherService::class,
            'middlewares' => [
                \App\Middlewares\SubMiddleware::class,
                \App\Middlewares\GroupTestMiddleware::class
            ]
        ],
        // ...    
    ];
```
