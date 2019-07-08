# 控制台日志

## 配置

启动应用里面 (`app\Application.php`) 重写父类方法，配置控制台日志参数。

```php
namespace App;


use Swoft\SwoftApplication;

/**
 * Class Application
 *
 * @since 2.0
 */
class Application extends SwoftApplication
{
    public function getCLoggerConfig(): array
    {
        return [
            'name'    => 'swoft',
            'enable'  => true,
            'output'  => true,
            'levels'  => 'info,error',
            'logFile' => ''
        ];
    }
}
```

- name 名称
- enable 是否开启
- output 是否打印到控制台
- levels 输入日志的级别，为空全部输出，具体日志级别配置值，可以引用 `Logger::NOTICE/...`
- logFile 控制台日志默认打印到控制台，也可以配置路径，同时写到指定文件

> swoft 2.0.3 `levels` 修改成字符串，方便开发者覆盖框架默认配置

## 使用

控制台日志可以直接使用框架提供的 `CLog` 类里面的静态方法操作。

```php
// debug
CLog::debug('debug');

// info 
CLog::info('info');

// warning
CLog::warning('warning');

// error
CLog::error('error');


// 2019/05/12-07:02:57 [DEBUG] Swoft\Processor\ConsoleProcessor:handle(33) debug
// 2019/05/12-07:02:57 [INFO] Swoft\Processor\ConsoleProcessor:handle(33) info
// 2019/05/12-07:02:57 [WARNING] Swoft\Processor\ConsoleProcessor:handle(33) warning
// 2019/05/12-07:02:57 [ERROR] Swoft\Processor\ConsoleProcessor:handle(33) error
```

- 每个日志级别方法都可以传递参数，底层是一个 `sprintf()` 函数封装
- debug 日志级别，需要开启 `SWOFT_DEBUG` 才会显示
- 框架内置不同级别不同颜色

## 关闭信息

默认情况下，启动时会打印一些启动信息到控制台。

> 如果你的 `.env` 开启了 `SWOFT_DEBUG=1` 将会看到更多详细的启动与加载信息。

如果你想关闭这些信息，可以在 `app/Application` 添加：

```php

    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();
        // disable print console start log
        $config['enable'] = false;
        
        return $config;
    }
```

重启swoft，可以看到不会有任何信息输出了

