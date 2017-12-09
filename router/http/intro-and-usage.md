# 路由器

## HTTP路由
路由解析有两种方式，注册路由和自动解析，所有路由都在 `app/routes.php` 中配置。
建议所有路由都提前注册，或者通过注解注册，不建议使用自动路由解析。路由配置参数\(config/beans/base.php\):

```php
return [
    // ...
    'httpRouter'      => [
        'class'          => \Swoft\Router\Http\HandlerMapping::class,
        'ignoreLastSep'  => false, // 是否忽略最后一个斜杠，设置false后，/user/index和/user/index/是两个不同的路由
        'tmpCacheNumber' => 1000,// 缓存路由数，最近一1000条(缓存到路由对象的，重启后失效，只会缓存动态路由)
        'matchAll'       => '', // 匹配所有，所有请求都会匹配到这个uri或闭包
    ],
    // ...
];


```

## RPC路由
路由配置参数\(config/beans/service.php\):

```php
return [
    // ...
    'serviceRouter' => [
        'class' => \Swoft\Router\Service\HandlerMapping::class,
        'suffix' => 'Service', // service文件后缀
    ],
    // ...
];

```

## 配置项说明

- `ignoreLastSep`  是否忽略最后一个斜杠，设置false后，`/user/index` 和 `/user/index/` 是两个不同的路由
- `tmpCacheNumber`  动态路由缓存数。已经匹配过的动态路由将会缓存到路由对象，重启后失效

### `matchAll` 匹配所有

配置 `matchAll` 可用于拦截所有请求。 （例如网站维护时）

可允许配置 `matchAll` 的值为 

- 路由path

```php
    'matchAll' => '/about', // a route path
```

- 回调

```php 
    'matchAll' => function () {
        echo '系统维护中 :)';
    },
```

### `autoRoute` 自动匹配路由(不推荐)

支持根据请求的URI自动匹配路由(就像 yii 一样), 需配置 `autoRoute`. 

```php 
    'autoRoute' => 1, // 启用
    'controllerNamespace' => 'App\\Controllers', // 控制器类所在命名空间
    'controllerSuffix' => 'Controller', // 控制器类后缀
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
    'params' => [
        'name' => '\w+', // 添加参数匹配限制。若不添加对应的限制，将会自动设置为匹配除了'/'外的任何字符
    ]
]);

// 可选参数支持。匹配 'hello' 'hello/john'
$router->get('/hello[/{name}]', function ($name = 'No') {
    echo $name; // 'john'
}, [
    'params' => [
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

## 添加路由方法说明

```php
public function map(string|array $methods, string $route, mixed $handler, array $opts = [])
```

添加路由方法

> 其他的添加路由方法`get() post() put() delete()`底层都是调用的 `map()` 方法，除了没有第一个参数外，其他参数都是一样的

- `$methods` string|array 请求的METHOD. e.g `GET` `['GET', 'POST]`
- `$route` string 定义的路由字符串 e.g `/user/login` `/article/{id}`
- `$handler` string|object 对应路由的处理者
- `$opts` array 选项设置，可以添加自定义的数据。匹配成功会将选项数据返回(e.g middleware, domains),自己再做进一步验证等。下面是已使用的选项
    - `params` 添加路由时设置的参数匹配信息, 若有的话 e.g `'name' => '\w+'`
    - `defaults` **有可选参数**时，可以设置默认值

一个较为完整的示例：

> 提示： 若不在选项中设置参数匹配，那么参数将会匹配除了 '/' 之外的任何字符

```php
$router->map(['get', 'post'], '/im/{name}[/{age}]', function(array $params) {
    var_dump($params);
}, [
    // 设置参数匹配
    'params' => [
        'name' => '\w+',
        'age' => '\d+',
    ],
    'defaults' => [
        'age' => 20, // 给可选参数 age 添加一个默认值
    ]
    
    // 可添加更多自定义设置信息
    // 'domains' => ['localhost'],
    // ... ...
]);
```

Now, 访问 `/im/john/18` 或者 `/im/john` 查看效果

