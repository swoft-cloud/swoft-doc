# DevTool 配置

要完整的启用 DevTool, 需要添加一些配置和进行一点操作。

## 开始配置

1. 在 `config/beans/base.php` 添加 HTTP 中间件让 DevTool 介入请求声明周期

```php
'serverDispatcher' => [
      'middlewares' => [
          // ...
          \Swoft\Devtool\Middleware\DevToolMiddleware::class,
      ]
 ],
```

2. DevTool 配置，用于标识是否启用某些功能(`config/properties/app.php`)，如不存在可自行添加配置
```php
'devtool' => [
    // 是否开启 DevTool，默认值为 false
    'enable' => true,
    // (可选)前台运行服务器时，是否打印事件调用到 Console
    'logEventToConsole' => true,
    // (可选)前台运行服务器时，是否打印 HTTP 请求到 Console
    'logHttpRequestToConsole' => true,
],
```

3. 发布 DevTool 的静态资源到项目的 `public` 目录

在项目目录下执行：

```bash
php bin/swoft dev:publish swoft/devtool
// -f 将会删除旧的资源，每次devtool更新后请都带上这个选项重新执行一次命令
php bin/swoft dev:publish swoft/devtool -f
```

4. 好了，现在你可以通过浏览器访问 `SCHEME://HOST:PORT/__devtool`（e.g. `http://127.0.0.1:80/__devtool`）

5. 如果你能看到下面的截图，说明已经成功安装并启用

![image](../images/devtool.jpg)

## 可能的问题

如果你访问这个地址 `HOST:PORT/__devtool` 报错或没有任何显示

- 确认访问地址正确，`HOST:PORT` + `/__devtool`
- 确认`PORT`是否正确，`PORT`为当前`HTTP服务`配置的端口
- 确认资源是否成功发布
- 确认你的 `public` 目录是可被浏览器访问的
- 确认安装或更新组件后 **重启** 了服务器

## 注意

> ！！打开 DevTool 会对服务器的运行和性能造成一定影响，请在进行压力测试前，将其关闭。



