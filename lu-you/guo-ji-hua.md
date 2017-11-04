# 国际化\(i18n\)

开发过程中，经常会涉及到多种语言，如果没有一个很好的语言管理，微小的一点改动都很麻烦。

## 配置

config/properties/app.php 里面配置国际化源语言路径。

```php
return [
    'I18n' =>[
        'sourceLanguage' => '@root/resources/messages/',
    ],
];
```

源语言目录格式，每种语言一个目录，每个目录下面有多个分类文件。

    |-- en
    |   |-- default.php
    |   `-- msg.php
    `-- zh
        |-- default.php
        `-- msg.php

分类文件定义格式，body是key，通过key来访问值，值的定义符合sprintf参数解析格式，无任何学习成本。

```php
return [
    'body' => 'this is msg [%s] %d',
];
```

## 使用

如果第一个参数\(分类\)有"."号，代码先查找分类文件，在查找key。如果没有，直接默认文件里面查找key。

```php
public function actionI18n()
{
    // 直接一个title,代表直接访问default.php的title key值
    $data[] = App::t("title", [], 'zh');
    $data[] = App::t("title", [], 'en');
    
    // 使用msg分类文件的body
    $data[] = App::t("msg.body", ["stelin", 999], 'en');
    $data[] = App::t("msg.body", ["stelin", 666], 'en');
    $this->outputJson($data);
}
```



