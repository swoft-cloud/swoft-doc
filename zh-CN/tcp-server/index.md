# Tcp 服务器

从 swoft `2.0.4` 版本开始，提供经过swoft封装的tcp 服务器实现。在原有swoole server的基础上，封装并细化功能使用。

> Available: `>= v2.0.4`

## 功能特色

- 基于swoft的注解系统，使用方便快速
- 提供统一的协议设置，同时支持EOF和length两种切包方式
- 完善的数据收发解析，统一的上下文/请求与响应对象封装
- 内置请求调度处理，可以像http一样细致的分发请求数据到不同的方法处理。
- 内置支持多种打包方式(`json` `php` `token`)，同时可以自由扩展。

## 安装

```bash
composer require swoft/tcp-server
```

## Git仓库

- tcp 数据协议 https://github.com/swoft-cloud/swoft-tcp
- tcp-server https://github.com/swoft-cloud/swoft-tcp-server

## 参与贡献

欢迎参与贡献，您可以

- fork 我们的开发仓库 [swoft/component](https://github.com/swoft-cloud/swoft-component)
- 修改代码然后发起 PR
- 关于发起PR的[注意事项](https://github.com/swoft-cloud/swoft/issues/829)
