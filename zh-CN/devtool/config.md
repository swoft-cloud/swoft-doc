# devtool 配置

要完整的启用 devtool, 需要添加一些配置和进行一点操作。

## 开始配置

1. 在 `config/beans/base.php` 添加http中间件

```php
'serverDispatcher' => [
      'middlewares' => [
          // ...
          \Swoft\Devtool\Middleware\DevToolMiddleware::class,
      ]
 ],
```

2. 还有一些特殊的配置，用于是否启用某些功能(`config/properties/app.php`).

> 这些配置是可选的，下面的是可配置项也是默认值

```php
'devtool' => [
	 // 前台运行服务器时，是否打印事件调用到console
    'logEventToConsole' => true,
	 // 前台运行服务器时，是否打印http请求到console
    'logHttpRequestToConsole' => true,
],
```

3. 发布devtool的静态资源到项目的 `public` 目录.

```bash
php bin/swoft dev:publish swoft/devtool
// -f 将会删除旧的资源，每次devtool更后请都带上这个选项重新执行一次命令
php bin/swoft dev:publish swoft/devtool -f
```

4. 好了，现在你可以通过浏览器访问 `HOST:PORT/__devtool`（e.g `http://127.0.0.1:9088/__devtool`）

5. 如果你能看到下面的截图，说明已经成功安装并启用

![image](../images/devtool.jpg)

## 可能的问题

如果你访问这个地址 `HOST:PORT/__devtool` 报错或没有任何显示

- 确认资源是否成功发布
- 确认你的 `public` 目录是可被浏览器访问的
- 确认安装或更新组件后 **重启** 了服务器

## 注意

！！打开devTool会对服务器的运行造成一定影响，请在进行压力测试前，将其关闭。



