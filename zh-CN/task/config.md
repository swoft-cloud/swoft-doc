# 任务配置
任务配置主要包括，任务扫描路径和是否开启定时任务配置

## 任务扫描配置
配置扫描任务的目录，app/config/properties/app.php

```php
return [
    // ....
    'beanScan'     => [
        // ....
        'App\Tasks',
        // ....
    ],
    // ....
];

```

## 定时任务开关

配置是否开启定时任务，.env

```php
CRONABLE=false
```


