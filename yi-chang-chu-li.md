# 异常处理

swoft通过定义ErrorHandler来处理全局异常，ErrorHandler只需要注入一个参数errorAction，错误统一处理action。

## 配置

base.php里面配置一个bean,如下：

```php
return [
        //...

        'errorHanlder' => [
                'class' => \swoft\web\ErrorHandler::class,
                'errorAction' => '/error/index', //错处理action
            ],

        // ...
];
```

## 错误控制器

控制器中可以通过请求Response对象获取错误异常\(所有错误转异常\)，逻辑很简单，照正常的一个action逻辑书写。如下:

```php
class ErrorController extends Controller
{
    /**
     * 错误action
     */
    public function actionIndex()
    {
        $response = App::getResponse();
        $exception = $response->getException();

        $status = $exception->getCode();
        $message  = $exception->getMessage();
        $line = $exception->getLine();
        $file = $exception->getFile();

        $message .= " ".$file." ".$line;
        $this->outputJson("error", $message, $status);
    }
}
```



