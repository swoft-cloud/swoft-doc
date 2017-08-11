# 全局配置

properties.php用于配置系统运行参数，这里面配置的参数，可以被base.php里面的bean引用，也可以在系统任何逻辑中使用。

```php
//properties.php
<?php
return [
    "version" => '1.0',
    'env' => 'base',
    'user.rpc.name' => 'user',
    'service' =>[
        'user' => [
            'timeout' => 21200
        ]
    ]
];
```

## Bean引用

引用有两种配置，直接配置和层级配置。

```php
// base.php
return [

    // ....
    "beanName"           => [
        "class"           => XXXX::class,
        "timeout"         => '${config.service.user.timeout}',// 层级配置
        'name'            => '${config.user.rpc.name}', // 直接配置
    ],
    // ....
];
```

## 业务使用

```php
$data = [];

// 数组使用
$version = App::$properties['version'];
$data['version'] = $version;

// 对象使用
$service = App::$properties->get('service');
$data['service'] = $service;

//迭代器使用
foreach (App::$properties as $key => $val) {
    $data['ary'][$key] = $val;
}
```



