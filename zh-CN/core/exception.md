# 异常处理

###1.编写的代码

首先我们在 app目录下创建一个 Exception目录，建立一个异常捕获的处理控制器SwoftExceptionHandler

用到的注解

`@ExceptionHandler()`  class注解，声明当前类是异常处理类
`@Handler()`  method注解，需要捕获的异常类; 例如Exception::class为php自带或者自己定义的异常处理类
```php
/**
 * @ExceptionHandler()
 * @package App\Exception
 */
class SwoftExceptionHandler
{
    /**
     * @Handler(Exception::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerException(Response $response, \Throwable $throwable)
    {
        $file      = $throwable->getFile();
        $line      = $throwable->getLine();
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        $data = ['msg' => $exception, 'file' => $file, 'line' => $line, 'code' => $code];
        App::error(json_encode($data));
        return $response->json($data);
    }
}
```

###2.配置能被swoft扫描到

在`config/properties/app.php`中 的`beanScan`中增加`App\Exception`, 配置如下

```php
return [
    'version'      => '1.0',
    'autoInitBean' => true,
    'bootScan'     => [
        'App\Commands',
    ],
    'beanScan'     => [
        'App\Controllers',
        'App\Models',
        'App\Middlewares',
        'App\Exception',
    ],
    'env'          => 'Base',
    'db'           => require __DIR__ . DS . 'db.php',
    'cache'        => require __DIR__ . DS . 'cache.php',
];
```

修改完成后，重新启动swoft即可。

