+++
title = "开发注意事项"
toc = true
type = "docs"
draft = false
date = "2018-09-19"
lastmod = "2018-09-20"

[menu.v2]
  parent = "dev-basis"
  weight = 4
+++

## 注意事项

- 禁止使用`$_GET、$_POST、$GLOBALS、$_SERVER、$_FILES、$_COOKIE、$_SESSION、$_REQUEST、$_ENV`等超全局变量
- 谨慎使用`global、static`关键字
- 不要在代码非协程环境中执行`sleep`以及其他睡眠函数，这样会导致整个进程阻塞. `exit/die` 是危险的，会导致 `worker` 进程退出
- 不要在业务代码中使用 `swoole` 不支持的 `hook` , 例如 `MongoDB`,`pgsql client`, 如果需要使用这些扩展需要单独开用户进程执行
- 无法 `hook` 的 `io` 都会同步阻塞进程, 导致`协程`无法切换, 直接的影响就是服务器大规模超时.
- 不建议使用 `curl` 扩展 类似的`GuzzleHttp`, 推荐使用 `swoft` 封装的网络请求包
- Swoole 短名开启 ,在 `php.ini` 中配置`swoole.use_shortname = 'on'`

## 代码格式

参照 [`PSR2`](https://www.php-fig.org/psr/psr-2/) 代码规范, 严格要求自己 .
