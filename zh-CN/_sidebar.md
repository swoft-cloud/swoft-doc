# Swoft

* 简介

  * [框架特性](README.md)
  * [关于Swoft](introduction/swoft.md)
  * [版本更新](introduction/update.md)
  * [常见问题](introduction/question.md)
  * [参与Swoft](introduction/join.md)

* 开始

  * [快速起步](quick-start/index.md)
  * [Annotations插件](quick-start/development.md)
  * [swoft安装](quick-start/install.md)
  * [运行服务](quick-start/start-swoft.md)
  * [应用结构](quick-start/project-skeleton.md)

* 技术说明

  * [技术说明](ready/10.index.md)
  * [传统架构](ready/20.tradition.md)
  * [IO复用](ready/30.io.md)
  * [Swoole 扩展](ready/40.swoole.md)
  * [Composer](ready/50.composer.md)
  * [上下文](ready/60.context.md)

* 注意

 * [特别注意](notice/index.md)
 * [严禁使用](notice/prohibited.md)
 * [内存泄漏排查](notice/memory-leak.md)
 * [性能优化](notice/performance-optimization.md)

* 注解

 * [注解说明](annotation/index.md)
 * [为什么使用注解](annotation/why.md)
 * [开发工具](annotation/tool.md)
 * [如何使用](annotation/usage.md)

* 配置

  * [关于配置](config/index.md)
  * [环境配置](config/env.md)
  * [应用配置](config/config.md)

* 容器

  * [容器](bean/index.md)
  * [Bean](bean/bean.md)
  * [单例](bean/singleton.md)
  * [Prototype](bean/prototype.md)
  * [请求实例](bean/request.md)
  * [接口注入](bean/interface.md)

* 事件

  * [关于事件](event/index.md)
  * [监听与触发](event/usage.md)
  * [Swoole事件](event/swoole-events.md)
  * [Swoft事件](event/swoft-events.md)

* 切面编程

  * [AOP切面编程](aop/index.md)
  * [切面声明](aop/statement.md)
  * [使用](aop/usage.md)
  * [顺序](aop/order.md)

* 错误处理

  * [错误处理](error/index.md)
  * [错误场景](error/scenes.md)
  * [添加处理器](error/usage.md)

* 公共方法

  * [公共方法](common/index.md)
  * [协程方法](common/co.md)
  * [定时器](common/timer.md)
  * [通用函数](common/generic.md)
  * [基础帮助库](common/stdlib.md)
    * [数组](common/stdlib-array.md)

----

* 命令行

  * [命令行说明](console/index.md)
  * [命令行配置](console/config.md)
  * [定义命令](console/definition.md)
  * [命令运行](console/usage.md)
  * [输入对象](console/input.md)
  * [输出对象](console/output.md)
  * [数据展示](console/data-show.md)

* Http 服务

  * [Http Server](http-server/index.md)
  * [常用命令](http-server/command.md)
  * [配置参数](http-server/setting.md)
  * [控制器](http-server/controller.md)
  * [路由绑定](http-server/route.md)
  * [请求对象](http-server/request.md)
  * [响应对象](http-server/response.md)
  * [中间件](http-server/middleware.md)
  * [异常处理](http-server/exception.md)
  * [HTTP 客户端](http-server/http.md)

* Websocket

  * [Websocket](websocket-server/index.md)
  * [配置服务](websocket-server/config.md)
  * [管理服务](websocket-server/manage.md)
  * [定义模块](websocket-server/module.md)
  * [消息控制器](websocket-server/message-route.md)
  * [消息发送](websocket-server/message-send.md)
  * [异常处理](websocket-server/exception.md)

* RPC 服务

  * [RPC Server](rpc-server/index.md)
  * [常用命令](rpc-server/command.md)
  * [配置参数](rpc-server/setting.md)
  * [声明服务](rpc-server/statement.md)

  * [RPC Client](rpc-client/index.md)
  * [配置服务](rpc-client/setting.md)
  * [如何使用](rpc-client/usage.md)
  * [1.0 RPC](rpc-client/rpc-1.0.md)

* TCP 服务

  * [TCP Server](tcp-server/index.md)
  * [配置服务](tcp-server/config.md)
  * [管理服务](tcp-server/manage.md)
  * [事件通知](tcp-server/event.md)
  * [控制器](tcp-server/controller.md)
  * [客户端通信](tcp-server/client-communicate.md)

* 进程

  * [进程介绍](process/index.md)
  * [进程](process/process.md)
  * [用户进程](process/user-process.md)
  * [进程池](process/process-pool.md)

* 任务

  * [任务](task/index.md)
  * [配置与启用](task/setting.md)
  * [声明一个任务](task/statement.md)
  * [协程任务](task/coroutine.md)
  * [异步任务](task/async.md)
  * [定时任务](task/crontab.md)

* 数据库

  * [数据库](db/index.md)
  * [配置](db/setting.md)
  * [模型](db/model.md)
  * [查询器](db/builder.md)
  * [事务](db/transaction.md)
  * [原生操作](db/origin.md)
  * [切库](db/selectDb.md)

* Redis

  * [Redis](redis/index.md)
  * [配置](redis/setting.md)
  * [如何使用](redis/usage.md)
  * [事务](redis/transaction.md)
  * [通道](redis/pipeline.md)
  * [发布订阅](redis/pub-sub.md)

* 国际化

  * [国际化](i18n/index.md)*
  * [配置参数](i18n/setting.md)
  * [如何使用](i18n/usage.md)

* 验证器

  * [验证器](validator/index.md)*
  * [配置](validator/setting.md)
  * [声明验证器](validator/statement.md)
  * [自定义验证器](validator/user-validator.md)
  * [自定义验证器规则](validator/customer-rule.md)
  * [控制器中使用](validator/controller-validator.md)
  * [非注解使用](validator/anywhere-validator.md)

* 日志

  * [日志](log/index.md)
  * [控制台日志](log/clog.md)
  * [应用日志](log/log.md)

-----

* 微服务

  * [服务治理](ms/govern/index.md)
  * [注册与发现](ms/govern/register-discovery.md)
  * [服务熔断](ms/govern/breaker.md)
  * [服务限流](ms/govern/limiter.md)
  * [配置中心](ms/govern/config.md)


-----

工具

* SwoftCli

  * [SwoftCli](tool/swoftcli/index.md)
  * [下载安装](tool/swoftcli/install.md)
  * [自动重启服务](tool/swoftcli/hot-restart.md)
  * [生成应用类文件](tool/swoftcli/generate-app-classs.md)
  * [创建新应用或组件](tool/swoftcli/create-app-or-component.md)
  * [打包应用为PHAR](tool/swoftcli/build-phar.md)

* Devtool

  * [Devtool](tool/devtool/index.md)
  * [实体生成](tool/devtool/entity.md)
  * [数据迁移](tool/devtool/migrations.md)

-----

* 扩展

  * [视图渲染](extra/view.md)
  * [Apollo](extra/apollo.md)
  * [缓存组件](extra/cache.md)
  * [Consul](extra/consul.md)
  * [Whoops](extra/whoops.md)
  * [PostgreSql](extra/postgresql.md)
  * [Http Session](extra/http-session.md)
  * [SwooleTracker](extra/swoole-tracker.md)

* 开发组件

  * [开发组件](component/index.md)
  * [组件结构](component/structure.md)
  * [组件入口](component/entry.md)
  * [组件加载](component/how-to-load.md)

* 最佳实践

  * [最佳实践](best-practices/index.md)
  * [常规架构](best-practices/architecture.md)
  * [Nginx配置](best-practices/nginx-config.md)

