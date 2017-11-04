# 异常处理

Swoft 通过 config/beans/base.php 配置的 application 里面的 `errorAction`，定义错误统一处理 Action。

## 配置

base.php里面配置一个bean,如下：

```php
return [
        //...

    'application'  => [
        'id'          => SYSTEM_NAME,
        'name'        => SYSTEM_NAME,
        'errorAction' => '/error/index', // 统一错误处理控制器
        'useProvider' => false,
    ],

        // ...
];
```

## 错误控制器

控制器中可以通过请求 Response 对象获取错误异常\(所有错误转异常\)，逻辑很简单，照正常的一个 action 逻辑书写。如下:

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



