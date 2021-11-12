# Swoft

* 简介

  * [框架特性](zh-CN/README.md)
  * [关于Swoft](zh-CN/introduction/swoft.md)
  * [版本更新](zh-CN/introduction/update.md)
  * [常见问题](zh-CN/introduction/question.md)
  * [参与Swoft](zh-CN/introduction/join.md)

* 开始

  * [快速起步](zh-CN/quick-start/index.md)
  * [Annotations插件](zh-CN/quick-start/development.md)
  * [swoft安装](zh-CN/quick-start/install.md)
  * [运行服务](zh-CN/quick-start/start-swoft.md)
  * [应用结构](zh-CN/quick-start/project-skeleton.md)

* 技术说明

  * [技术说明](zh-CN/ready/10.index.md)
  * [传统架构](zh-CN/ready/20.tradition.md)
  * [IO复用](zh-CN/ready/30.io.md)
  * [Swoole 扩展](zh-CN/ready/40.swoole.md)
  * [Composer](zh-CN/ready/50.composer.md)
  * [上下文](zh-CN/ready/60.context.md)

* 注意

 * [特别注意](zh-CN/notice/index.md)
 * [严禁使用](zh-CN/notice/prohibited.md)
 * [内存泄漏排查](zh-CN/notice/memory-leak.md)
 * [性能优化](zh-CN/notice/performance-optimization.md)

* 注解

 * [注解说明](zh-CN/annotation/index.md)
 * [为什么使用注解](zh-CN/annotation/why.md)
 * [开发工具](zh-CN/annotation/tool.md)
 * [如何使用](zh-CN/annotation/usage.md)

* 配置

  * [关于配置](zh-CN/config/index.md)
  * [环境配置](zh-CN/config/env.md)
  * [应用配置](zh-CN/config/config.md)

* 容器

  * [容器](zh-CN/bean/index.md)
  * [Bean](zh-CN/bean/bean.md)
  * [单例](zh-CN/bean/singleton.md)
  * [Prototype](zh-CN/bean/prototype.md)
  * [请求实例](zh-CN/bean/request.md)
  * [接口注入](zh-CN/bean/interface.md)

* 事件

  * [关于事件](zh-CN/event/index.md)
  * [监听与触发](zh-CN/event/usage.md)
  * [Swoole事件](zh-CN/event/swoole-events.md)
  * [Swoft事件](zh-CN/event/swoft-events.md)

* 切面编程

  * [AOP切面编程](zh-CN/aop/index.md)
  * [切面声明](zh-CN/aop/statement.md)
  * [使用](zh-CN/aop/usage.md)
  * [顺序](zh-CN/aop/order.md)

* 错误处理

  * [错误处理](zh-CN/error/index.md)
  * [错误场景](zh-CN/error/scenes.md)
  * [添加处理器](zh-CN/error/usage.md)

* 公共方法

  * [公共方法](zh-CN/common/index.md)
  * [协程方法](zh-CN/common/co.md)
  * [定时器](zh-CN/common/timer.md)
  * [通用函数](zh-CN/common/generic.md)
  * [基础帮助库](zh-CN/common/stdlib.md)
    * [数组](zh-CN/common/stdlib-array.md)

----

* 命令行

  * [命令行说明](zh-CN/console/index.md)
  * [命令行配置](zh-CN/console/config.md)
  * [定义命令](zh-CN/console/definition.md)
  * [命令运行](zh-CN/console/usage.md)
  * [输入对象](zh-CN/console/input.md)
  * [输出对象](zh-CN/console/output.md)
  * [数据展示](zh-CN/console/data-show.md)

* Http 服务

  * [Http Server](zh-CN/http-server/index.md)
  * [常用命令](zh-CN/http-server/command.md)
  * [配置参数](zh-CN/http-server/setting.md)
  * [控制器](zh-CN/http-server/controller.md)
  * [路由绑定](zh-CN/http-server/route.md)
  * [请求对象](zh-CN/http-server/request.md)
  * [响应对象](zh-CN/http-server/response.md)
  * [中间件](zh-CN/http-server/middleware.md)
  * [异常处理](zh-CN/http-server/exception.md)
  * [HTTP 客户端](zh-CN/http-server/http.md)

* Websocket

  * [Websocket](zh-CN/websocket-server/index.md)
  * [配置服务](zh-CN/websocket-server/config.md)
  * [管理服务](zh-CN/websocket-server/manage.md)
  * [定义模块](zh-CN/websocket-server/module.md)
  * [消息控制器](zh-CN/websocket-server/message-route.md)
  * [消息发送](zh-CN/websocket-server/message-send.md)
  * [异常处理](zh-CN/websocket-server/exception.md)

* RPC 服务

  * [RPC Server](zh-CN/rpc-server/index.md)
  * [常用命令](zh-CN/rpc-server/command.md)
  * [配置参数](zh-CN/rpc-server/setting.md)
  * [声明服务](zh-CN/rpc-server/statement.md)

  * [RPC Client](zh-CN/rpc-client/index.md)
  * [配置服务](zh-CN/rpc-client/setting.md)
  * [如何使用](zh-CN/rpc-client/usage.md)
  * [1.0 RPC](zh-CN/rpc-client/rpc-1.0.md)

* TCP 服务

  * [TCP Server](zh-CN/tcp-server/index.md)
  * [配置服务](zh-CN/tcp-server/config.md)
  * [管理服务](zh-CN/tcp-server/manage.md)
  * [事件通知](zh-CN/tcp-server/event.md)
  * [控制器](zh-CN/tcp-server/controller.md)
  * [客户端通信](zh-CN/tcp-server/client-communicate.md)

* 进程

  * [进程介绍](zh-CN/process/index.md)
  * [进程](zh-CN/process/process.md)
  * [用户进程](zh-CN/process/user-process.md)
  * [进程池](zh-CN/process/process-pool.md)

* 任务

  * [任务](zh-CN/task/index.md)
  * [配置与启用](zh-CN/task/setting.md)
  * [声明一个任务](zh-CN/task/statement.md)
  * [协程任务](zh-CN/task/coroutine.md)
  * [异步任务](zh-CN/task/async.md)
  * [定时任务](zh-CN/task/crontab.md)

* 数据库

  * [数据库](zh-CN/db/index.md)
  * [配置](zh-CN/db/setting.md)
  * [模型](zh-CN/db/model.md)
  * [查询器](zh-CN/db/builder.md)
  * [事务](zh-CN/db/transaction.md)
  * [原生操作](zh-CN/db/origin.md)
  * [切库](zh-CN/db/selectDb.md)

* Redis

  * [Redis](zh-CN/redis/index.md)
  * [配置](zh-CN/redis/setting.md)
  * [如何使用](zh-CN/redis/usage.md)
  * [事务](zh-CN/redis/transaction.md)
  * [通道](zh-CN/redis/pipeline.md)
  * [发布订阅](zh-CN/redis/pub-sub.md)

* 国际化

  * [国际化](zh-CN/i18n/index.md)*
  * [配置参数](zh-CN/i18n/setting.md)
  * [如何使用](zh-CN/i18n/usage.md)

* 验证器

  * [验证器](zh-CN/validator/index.md)*
  * [配置](zh-CN/validator/setting.md)
  * [声明验证器](zh-CN/validator/statement.md)
  * [自定义验证器](zh-CN/validator/user-validator.md)
  * [自定义验证器规则](zh-CN/validator/customer-rule.md)
  * [控制器中使用](zh-CN/validator/controller-validator.md)
  * [非注解使用](zh-CN/validator/anywhere-validator.md)

* 日志

  * [日志](zh-CN/log/index.md)
  * [控制台日志](zh-CN/log/clog.md)
  * [应用日志](zh-CN/log/log.md)

-----

* 微服务

  * [服务治理](zh-CN/ms/govern/index.md)
  * [注册与发现](zh-CN/ms/govern/register-discovery.md)
  * [服务熔断](zh-CN/ms/govern/breaker.md)
  * [服务限流](zh-CN/ms/govern/limiter.md)
  * [配置中心](zh-CN/ms/govern/config.md)


-----

工具

* SwoftCli

  * [SwoftCli](zh-CN/tool/swoftcli/index.md)
  * [下载安装](zh-CN/tool/swoftcli/install.md)
  * [自动重启服务](zh-CN/tool/swoftcli/hot-restart.md)
  * [生成应用类文件](zh-CN/tool/swoftcli/generate-app-classs.md)
  * [创建新应用或组件](zh-CN/tool/swoftcli/create-app-or-component.md)
  * [打包应用为PHAR](zh-CN/tool/swoftcli/build-phar.md)

* Devtool

  * [Devtool](zh-CN/tool/devtool/index.md)
  * [实体生成](zh-CN/tool/devtool/entity.md)
  * [数据迁移](zh-CN/tool/devtool/migrations.md)

-----

* 扩展

  * [视图渲染](zh-CN/extra/view.md)
  * [Apollo](zh-CN/extra/apollo.md)
  * [缓存组件](zh-CN/extra/cache.md)
  * [Consul](zh-CN/extra/consul.md)
  * [Whoops](zh-CN/extra/whoops.md)
  * [PostgreSql](zh-CN/extra/postgresql.md)
  * [Http Session](zh-CN/extra/http-session.md)
  * [SwooleTracker](zh-CN/extra/swoole-tracker.md)

* 开发组件

  * [开发组件](zh-CN/component/index.md)
  * [组件结构](zh-CN/component/structure.md)
  * [组件入口](zh-CN/component/entry.md)
  * [组件加载](zh-CN/component/how-to-load.md)

* 最佳实践

  * [最佳实践](zh-CN/best-practices/index.md)
  * [常规架构](zh-CN/best-practices/architecture.md)
  * [Nginx配置](zh-CN/best-practices/nginx-config.md)

