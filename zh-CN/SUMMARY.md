# Summary

## 简介

  * [框架特性](README.md)
  * [关于 Swoft](introduction/swoft.md)
  * [版本更新](introduction/update.md)
  * [常见问题](introduction/question.md)
  * [参与 Swoft](introduction/join.md)

## 开始
  
### [快速起步](quick-start/index.md)

  * [Annotations插件](quick-start/development.md)
  * [swoft安装](quick-start/install.md)
  * [运行服务](quick-start/start-swoft.md)
  * [应用结构](quick-start/project-skeleton.md)
  
### [技术说明](ready/index.md)

  * [传统架构](ready/tradition.md)
  * [IO复用](ready/io.md)
  * [Swoole 扩展](ready/swoole.md)
  * [Composer](ready/composer.md)

### [特别注意](notice/index.md)

  * [严禁使用](notice/prohibited.md)
  * [内存泄漏排查](notice/memory-leak.md)
  * [性能优化](notice/performance-optimization.md)
  
## 基础

### [注解](annotation/index.md)

  * [为什么使用注解](annotation/why.md)
  * [开发工具](annotation/tool.md)
  * [如何使用](annotation/usage.md)
  
### [配置](config/index.md)

  * [环境配置](config/env.md)
  * [应用配置](config/config.md)
  
### [容器](bean/index.md)

  * [Bean](bean/bean.md)
  * [单例](bean/singleton.md)
  * [Prototype](bean/prototype.md)
  * [请求实例](bean/request.md)
  * [接口注入](bean/interface.md)

### [事件](event/index.md)

  * [监听与触发](event/usage.md)
  * [Swoole事件](event/swoole-events.md)
  * [Swoft事件](event/swoft-events.md)

### [切面编程](aop/index.md)

  * [声明](aop/statement.md)
  * [使用](aop/usage.md)
  * [顺序](aop/order.md)

### [错误处理](error/index.md)

  * [添加处理器](error/usage.md)

### [公共方法](common/index.md)

  * [协程方法](common/co.md)
  * [通用函数](common/generic.md)
  * [基础帮助库](common/stdlib.md)

## 核心

### [命令行](console/index.md)

  * [命令行配置](console/config.md)
  * [定义命令](console/definition.md)
  * [命令运行](console/usage.md)
  * [输入对象](console/input.md)
  * [输出对象](console/output.md)
  * [数据展示](console/data-show.md)

### [Http Server](http-server/index.md)

  * [常用命令](http-server/command.md)
  * [配置参数](http-server/setting.md)
  * [执行流程](http-server/process.md)
  * [控制器](http-server/controller.md)
  * [路由绑定](http-server/route.md)
  * [请求对象](http-server/request.md)
  * [响应对象](http-server/response.md)
  * [中间件](http-server/middleware.md)
  * [异常处理](http-server/exception.md)
  * [HTTP 客户端](extra/http.md)

### [Websocket](websocket-server/index.md)

  * [配置服务](websocket-server/config.md)
  * [管理服务](websocket-server/manage.md)
  * [websocket模块](websocket-server/module.md)
  * [消息处理](websocket-server/message-route.md)
  * [消息发送](websocket-server/message-send.md)
  * [异常处理](websocket-server/exception.md)

### [RPC Server](rpc-server/index.md)

  * [常用命令](rpc-server/command.md)
  * [配置参数](rpc-server/setting.md)
  * [声明服务](rpc-server/statement.md)
  
### [RPC Client](rpc-client/index.md)

  * [配置服务](rpc-client/setting.md)
  * [如何使用](rpc-client/usage.md)
  
### [任务](task/index.md)

  * [配置与启用](task/setting.md)
  * [声明一个任务](task/statement.md)
  * [协程任务](task/coroutine.md)
  * [异步任务](task/async.md)

### [数据库](db/index.md)

  * [配置](db/setting.md)
  * [模型](db/model.md)
  * [查询器](db/builder.md)
  * [事务](db/transaction.md)
  * [原生操作](db/origin.md)
  * [切库](db/selectDb.md)

  
### [Redis](redis/index.md)

  * [配置](redis/setting.md)
  * [如何使用](redis/usage.md)
  * [事务](redis/transaction.md)
  * [通道](redis/pipeline.md)
  * [发布订阅](redis/pub-sub.md)
  
### [国际化](i18n/index.md)

  * [配置参数](i18n/setting.md)
  * [如何使用](i18n/usage.md)
  
### [验证器](validator/index.md)

  * [声明验证器](validator/statement.md)
  * [自定义验证器](validator/user-validator.md)
  * [自定义验证器规则](validator/customer-rule.md)
  * [控制器中使用](validator/controller-validator.md)
  * [非注解使用](validator/anywhere-validator.md)
    
### [日志](log/index.md)

  * [控制台日志](log/clog.md)
  * [应用日志](log/log.md)
  
## 微服务

### [服务治理](ms/govern/index.md)
  * [服务注册与发现](ms/govern/register-discovery.md)
  * [熔断](ms/govern/breaker.md)
  * [限流](ms/govern/limit.md)
  * [配置中心](ms/govern/config.md)

## 工具

### [SwoftCli](tool/swoftcli/index.md)

  * [下载安装](tool/swoftcli/install.md)
  * [自动重启服务](tool/swoftcli/hot-restart.md)
  * [打包应用为PHAR](tool/swoftcli/build-phar.md)

### [Devtool](tool/devtool/index.md)
  * [实体生成](tool/devtool/entity.md)

## 扩展

  * [视图渲染](extra/view.md)
  * [如何编写组件](extra/write-component.md)

### [最佳实践](best-practices/index.md)

  * [常规架构](best-practices/architecture.md)
  * [中间件简介](best-practices/middleware.md)
  * [部署与使用](best-practices/deploy.md)
  * [演示案例](best-practices/demo.md)
  * [Nginx配置](best-practices/nginx-config.md)

