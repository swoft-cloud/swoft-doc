# Http 会话管理

自 Swoft `v2.0.7` 官方提供了新的Http 会话管理组件。

> github - https://github.com/swoft-cloud/swoft-session

内置支持的驱动处理器：

- `file` 本机文件处理器
- `coFile` 本机文件处理器，但是使用swoole的协程方法读写内容
- `array` php内存驱动处理器，使用php array存储数据。_用于测试，数据仅在当前进程保存_
- `memTable` 使用swoole table 存储数据

## 安装

http session 作为一个额外的功能组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/session
```

- 通过 composer.json 配置:

```json
    "swoft/session": "~2.0.0"
```

## 配置组件

当你安装了 session 组件后，需要配置http 全局中间件来使用它(`app/bean.php`)。

```php
  'httpDispatcher'    => [
        // Add global http middleware
        'middlewares'      => [
            \Swoft\Http\Session\SessionMiddleware::class,
        ],
   ],
```

- 视图组件注册到容器里的名称为： `view`
- bean配置(file: `app/beans.php`)

```php
'view' => [
    // class 配置是可以省略的, 因为 view 组件里已经配置了它
    // 'class' => \Swoft\View\Renderer::class,
    'viewsPath' => dirname(__DIR__) . '/resource/views/',
],
```

现在在任何地方都可以通过 `view()` OR `\Swoft::getBean('view')` 来获取组件实例。

### 配置项说明

- `viewsPath` 视图存放路径
- `layout` 默认的布局文件。 调用 `render()` 方法时会默认的使用它
- `suffix` 默认的视图后缀(默认是 `php`)
- `suffixes` 允许的视图后缀列表。 用于判断是否需要添加默认后缀
- `placeholder` 在布局文件里使用的内容占位符。 默认 `{_CONTENT_}`
