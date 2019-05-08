## 配置参数

可以在 bean.php 里面配置参数信息

```php
return [
    'i18n'   => [
        'resourcePath' => '@resource/language/',
    ],
];
```
- resourcePath 配置语言文件类型
- defualtCategory 配置语言文件默认名称, 默认 `default` 即是 `default.php`文件
- defaultLanguage 配置默认是语言，默认是 `en`

## 语言文件

语言文件就是一个 PHP 文件，但是要返回一个数组且数组值是 KV 格式，方便使用。如下配置格式

```
|-- zh
    |-- default.php
    |-- msg.php
`-- en
    |-- default.php
    |-- msg.php
```

文件内容如下：

```php
// en/default.php
return [
    'name' => 'name {name}'
];

// en/msg.php
return [
    'name' => 'msg name {name}',
];

// zh/default.php
return [
    'name' => '名称 {name}'
];

// zh/msg.php
return [
    'name' => '消息名称 {name}'
];
```
