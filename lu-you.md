# 路由器

路由解析有两种方式，注册路由和自动解析，所有路由都在app/routes.php 中配置。建议所有路由都提前注册，或者通过注解注册，不建议使用自动路由解析。路由配置参数\(config/beans/base.php\):

```php
return [
    // ...
    'router'      => [
        'class'  => \Swoft\Web\Router::class,
        'ignoreLastSep'  => false, // 是否忽略最后一个斜杠，设置false后，/user/index和/user/index/是两个不同的路由
        'tmpCacheNumber' => 1000,// 缓存路由数，最近一1000条
        'matchAll'       => '', // 匹配所有，所有请求都会匹配到这个uri或闭包
    ],
    // ...
];
```

## 路由注册实例

```php
//匹配 GET 请求. 处理器是个闭包 Closure
$router->get('/', function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("hello");
    $resposne->send();
});

// 匹配参数 'test/john'
$router->get('/test/{name}', function ($arg) {
    echo $arg; // 'john'
}, [
    'tokens' => [
        'name' => '\w+', // 添加参数匹配限制。若不添加对应的限制，将会自动设置为匹配除了'/'外的任何字符
    ]
]);

// 可选参数支持。匹配 'hello' 'hello/john'
$router->get('/hello[/{name}]', function ($name = 'No') {
    echo $name; // 'john'
}, [
    'tokens' => [
        'name' => '\w+', // 添加参数匹配限制
    ]
]);

// 匹配 POST 请求
$router->post('/user/login', function () {
    $request = App::getRequest();
    var_dump($request->getGetParameters(), $request->getPostParameters());
});

// 匹配 GET 或者 POST
$router->map(['get', 'post'], '/user/login', function () {
    $request = App::getRequest();
    var_dump($request->getGetParameters(), $request->getPostParameters());
});

// 允许任何请求方法
$router->any('/home', function () {
    $resposne = RequestContext::getResponse();
    $resposne->setResponseContent("hello, you request page is /home");
    $resposne->send();
});
$router->any('/404', function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("Sorry,This page not found.");
    $resposne->send();
});

// 路由组
$router->group('/user', function ($router) {
    $router->get('/', function () {
        $resposne = App::getResponse();
        $resposne->setResponseContent("hello. you access: /user/");
        $resposne->send();
    });
    $router->get('/index', function () {
        $resposne = App::getResponse();
        $resposne->setResponseContent("hello. you access: /user/index");
        $resposne->send();
    });
});

// 通过@符号连接控制器类和方法名可以指定执行方法
$router->get('/', app\controllers\Home::class);
$router->get('/index', 'app\controllers\Home@index');
$router->get('/about', 'app\controllers\Home@about');

// 访问 '/home/test' 将会执行 'app\controllers\Home::test()'
$router->any('/home/{any}', app\controllers\Home::class);

// 可匹配 '/home', '/home/test' 等
$router->any('/home[/{name}]', app\controllers\Home::class);

// 配置 matchAll 可用于拦截所有请求，目前有如下两种方式。
//路由path
'matchAll' => '/about',

//回调
'matchAll' => function () {
    $resposne = App::getResponse();
    $resposne->setResponseContent("System Maintaining ... ...");
    $resposne->send();
},
```

## 



