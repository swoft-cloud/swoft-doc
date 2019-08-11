## 配置参数
使用 i18n 功能,一共需要三步

- 资源文件夹中导入语言文本
- 配置文件中配置相关参数
- 调用相关方法完成文本转换


### 1.导入文本
我们在 resource/language/ 文件夹下可创建语言组文件夹与文本文件，一个语言组中可以有多个模板文件，如下所示
``` shell
# resource/language
|-- zh
    |-- default.php
    |-- msg.php
`-- en
    |-- default.php
    |-- msg.php
```

而文本格式是由关联数组组成.
键值为文本,我们可以在其中插入参数,格式大致如下:
``` php
// ../en/default.php
return [
    // 文本中可用大括号注入参数
    'sayhello' => 'Hey {name}!',
    'saygoodbye' => 'Bye!',
];

//  ../en/msg.php
return [
    'sayhello' => "Wath's up! {name}",
    'saygoodbye' => 'See you tomorrow!',
];

// ../zh/default.php
return [
    'sayhello' => "早上好,{name}",
    'saygoodbye' => '再见',
];
// ../zh/msg.php
return [
    'sayhello' => "晚上好,{name}",
    'saygoodbye' => '明天见',
];
```
### 2.相关配置

i18n相关功能配置非常简单,
只需要在 app/bean.php 配置文件中,配置以下参数,即可开启国际化功能.参数描述见注释
``` php
return [
    // .... 其他配置

    'i18n'  => [
        // 设置到文本资源目录
        'resoucePath' => '@resource/language/', // 结尾斜线必须

        // 设置默认文本文件夹名称
        // 未填写则默认 en 文件夹
        'defaultLanguage'   => 'en',

        // 设置默认文本文件名称
        // 未填写则默认 default.php
        'defualtCategory'   => 'default',
    ],

    // .... 其他配置
];
```
至此相关参数已配置完毕,下面就可以使用该功能了.
