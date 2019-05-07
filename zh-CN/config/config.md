## 配置

可以在 bean.php 文件配置应用配置参数。

```php
return [
    'config'   => [
        'path' => __DIR__ . '/../config',
    ],
];
```

- path 自定配置文件路径
- base 主文件名称，默认 `base`
- type 配置文件类型，默认 `php` 同时也支持 `yaml` 格式
- parser 配置解析器，默认已经配置 php/yaml 解析器。


## 解析格式

配置目录所有配置文件会解析成一个数组，其它文件或子目录，以它的文件名和目录名称为数组 key 进行合并数组。比如 config 目录配置文件如下

```
|-- base.php
|-- data.php
`-- user
    |-- sms.php
```

base.php

```php
return [
    'key' => 'value'
];
```

data.php

```php
return [
    'dkey' => [
        'dvalue'
    ]
];
```

sms.php

```php
return [
    'skey' => 'svalue'
];
```

如上配置文件，合并的数据格式如下：

```php
return [
    'key' => 'value',
    'data' => [
        'dkey' => [
            'dvalue'
        ]
    ],
    'user' => [
        'sms' => [
            'skey' => 'svalue'
        ]
    ]
];
```


## 使用

框架提全局函数、注解、config 对象多种方式，使用应用配置数据。

### 函数

全局函数使用 `config()`
```
config(string $key, mixed $default = null):mixed
```

- key 配置参数 key，子数组可以使用 `.` 分割，比如上面的例子 `data.dkey` 可以获取到 `["dvalue"]`
- default 默认参数，如果 key 参数不存在，返回默认值，默认值可以是任意类型

### 注解

通过容器使用注解的方式，注入配置到属性值。

```php
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;

/**
* @Bean()
*/
class Demo
{
    /**
    * @Config("data.dkey")
    */
    private $dvalue = [];
    
    // ...
}
```

此例子和上面功能一样，都是读取相同的数据，两种不同的方式。

> 使用注解，一定要保证类是一个bean对象(通过其它注解注入到容器)

### 对象

如果上面两种方式还不能满足你的业务需求，你可以从容器里面获取配置对象，里面自带很多方式操作配置数据。

```php
use Swoft\Bean\BeanFactory;

$config = BeanFactory::getBean('config');
```

config 对象常用方法

- get(string $key, $default = null) 获取参数
- offsetGet($key) 获取参数
- ....