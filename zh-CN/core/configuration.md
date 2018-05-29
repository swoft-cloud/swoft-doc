# 配置

swoft 的配置与其他框架稍微有一些区别，有几份：

- `config/server.php` swoft 服务器配置，主要是对swoole的配置
- `config/beans/*` swoft beans 配置，用于配置一些通过数组方式创建的bean，关于bean的创建方式请查看相关章节。
- `config/properties/*` 这里面就是通常的 **应用配置** 数据了
  - 只会加载里面的 `app.php` 文件，其他文件都是在 `app.php` 内部include进来的

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
