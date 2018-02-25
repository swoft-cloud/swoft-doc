# 进程配置
进程配置主要是配置定义的进程文件扫描目录，分为两种不同的情况。

## Server 前置进程

配置Server 前置进程扫描目录，app/config/properties/app.php

```php
return [
    // ....
    'bootScan'     => [
        // ....
        'App\Boot',
        // ....
    ],
    // ....
];

```

## 自定义进程
配置自定义进程扫描目录，app/config/properties/app.php
```php
return [
    // ....
    'beanScan'     => [
        // ....
        'App\Process',
        // ....
    ],
    // ....
];

```