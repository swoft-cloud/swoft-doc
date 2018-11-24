# 组件

swoft 应用由一个个组件构成，核心为 `swoft-framework` 组件。

## 基本原理

组件可以拥有独立的功能，注解，命令等。通过监听swoft提供的事件可以增加一些自定义功能

## 组件注册

### 内部组件

`app/` 目录和 `vendor/swoft` 会被自动的扫描，收集注解信息。

### 自定义组件

在配置文件 `config/properties/app.php` 添加如下配置

```php
'components' => [
    'custom' => [
        // Your package namespace.
        'Package\\Namespace',
    ],
],
```

之后，swoft启动时就会到对应的包里去扫描，收集信息

## 如何实现组件

> TODO

## 官方组件

Swoft 采用组件化的开发模式，可自由组合各个组件来满足自己的业务实现，在简单业务下可以免于臃肿，在复杂业务下又游刃有余。  
以下为官方团队开发提供的组件

> 若有优秀的组件亦可在 Github 上提交 [awesome-swoft](https://github.com/swoft-cloud/awesome-swoft) 增加在此列表上  

组件名称 | 维护者 | 简述
:- | :- | :- |
swoft-framework | 官方 | 框架基础及核心
swoft-http-server | 官方 | HTTP 服务组件
swoft-http-message | 官方 | 基于 PSR-7 实现的 HTTP 消息组件
swoft-http-client | 官方 | 基于 PSR-7 实现的 HTTP 客户端
swoft-rpc-server | 官方 | RPC 服务组件
swoft-rpc | 官方 | RPC 基础组件
swoft-rpc-client | 官方 | RPC 客户端组件
swoft-db | 官方 | 数据库组件
swoft-view | 官方 | 视图组件
swoft-process | 官方 | 进程组件
swoft-task | 官方 | 任务及计划任务组件
swoft-service-governance | 官方 | 服务治理组件
swoft-session | 官方 | Session 会话组件
swoft-console | 官方 | 控制台组件
swoft-cache | 官方 | 基于 PSR-16 实现的缓存组件
swoft-log | 官方 | 日志组件
swoft-memory | 官方 | 内存操作组件
swoft-redis | 官方 | 异步协程的 Redis 组件
swoft-i18n | 官方 | 国际化组件

