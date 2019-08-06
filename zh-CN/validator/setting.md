# 配置

## 安装

使用验证器前，需要安装验证器组件，安装如下：

```php
composer require swoft/validator
```

## 启用

成功安装好验证组件后，接下来需要启用验证器，这里以 Http-server 启用为例，其它一样(app/bean.php)

```php
return [
    // ......
    'httpDispatcher'    => [
        // ......
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class
        ]
        // ......
    ],
    // ......
];
```

> 2.0.5+ 验证器默认没有启动，需要开发者自己开启