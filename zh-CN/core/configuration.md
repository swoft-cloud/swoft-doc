# 配置

swoft 的配置与其他框架稍微有一些区别，有几份：

- `config/server.php` swoft 服务器配置，主要是对swoole的配置
  - 里面的 `setting`, 对应了swoole server的配置，设置后将会原样的应用于swoole server上。[swoole setting 参考](https://wiki.swoole.com/wiki/page/274.html)
- `config/beans/*` swoft beans 配置，用于配置一些通过数组方式创建的bean，关于bean的创建方式请查看相关章节。
- `config/properties/*` 这里面就是通常的 **应用配置** 数据了
  - 只会加载里面的 `app.php` 文件，其他文件都是在 `app.php` 内部include进来的
- `config/define.php` 定义了一些通用的常量和路径别名

> 当然 `.env` 也是一份配置，它总是最先被载入进来，以方便后面的使用。

## 配置的使用

配置的使用主要是指 应用配置 数据的获取使用

### 应用配置

应用配置对象是 `Swoft\Core\Config` 的实例。

获取配置对象：

- `\Swoft::getProperties()` OR `\Swoft\App::getProperties()`
- `\Swoft::getBean('config')` OR `\Swoft\App::getBean('config')`

获取配置:

```php
// 通过全局函数使用
$value = \config('key', 'default value');
// 通过 "." 符号获取子级配置
$value = \config('section.subkey', 'default value');

// 通过获取config对象来使用
$config = \Swoft::getBean('config');

$value = $config->get('key', 'default value');
$value = $config->get('section.subkey', 'default value');

// 设置值
$config->set('my-name', 'tom');
echo $config->get('my-name'); // tom
```

## 路径别名

路径别名 主要是配置了一些常用的路径，给他们取了一些别名以方便使用。

### 已有别名

```php
// Register aliases
$aliases = [
    '@root'       => BASE_PATH, // 项目所在路径
    '@env'        => '@root',
    '@app'        => '@root/app',
    '@res'        => '@root/resources',
    '@runtime'    => '@root/runtime',
    '@configs'    => '@root/config',
    '@resources'  => '@root/resources',
    '@beans'      => '@configs/beans',
    '@properties' => '@configs/properties',
    '@console'    => '@beans/console.php',
    '@commands'   => '@app/command',
    '@vendor'     => '@root/vendor',
];

\Swoft\App::setAliases($aliases);
```

如有需要，您可以添加自定义的别名

### 别名使用

```php
// 通过全局函数使用
$path = \alias('@app');
$path = \alias('@app/Controllers');

// 通过顶级类 \Swoft 使用
$path = \Swoft::getAlias('@app');
$path = \Swoft::getAlias('@app/Controllers');
```

获取全部的别名：

```php
$aliaes = \Swoft::getAliases();
```

