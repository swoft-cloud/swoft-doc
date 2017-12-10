# service中间件

用户可以自定义一些中间，满足不同的业务场景。service中可以通过注解和配置两种方式使用已经定义好的中间。

## 注解使用

### @Middleware

- @Middleware，可以指定一个中间件名称
- 多个@Middleware中间件，按照配置顺序执行
- 如果定义在service类上，作用于整个类的所有方法函数
- 如果定义在一个函数上面，仅仅作用于当前方法函数

### @Middlewares

- @Middlewares，通过一个@Middleware数组，定义一个组中间件
- @Middlewares，里面多个@Middleware中间件，按照配置顺序执行
- 如果定义在service类上，作用于整个类的所有方法函数
- 如果定义在一个函数上面，仅仅作用于当前方法函数

### 使用实例

```php
/**
 * the middleware of service
 *
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

### 配置使用

用户也可以通过配置(app/config/beans/base.php)使用注解，执行顺序按照配置数组顺序。

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