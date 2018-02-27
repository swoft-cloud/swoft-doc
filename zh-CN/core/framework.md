# 整体架构

## 简介
这一章节将帮助你了解 Swoft 是如何工作的，通过更好的了解整个框架，这有助于你更加清楚自己在做什么，更加清楚自己想实现的功能应该如何去实现，以及遇到问题时如何去定位问题。

## 应用组件
Swoft 采用组件化的开发模式，可自由组合各个组件来满足自己的业务实现，在简单业务下可以免于臃肿，在复杂业务下又游刃有余。  
以下为官方团队开发提供的组件，若有优秀的组件亦可联系 Swoft开发团队 增加在此列表上  


| 组件名称 | 维护者 | 简述 |
| :- | :- | :- |  
| swoft-framework | 官方 |  |
| swoft-http-server | 官方 |  |
| swoft-http-message | 官方 |  |
| swoft-http-client | 官方 |  |
| swoft-rpc-server | 官方 |  |
| swoft-rpc | 官方 |  |
| swoft-rpc-client | 官方 |  |
| swoft-db | 官方 |  |
| swoft-view | 官方 |  |
| swoft-process | 官方 |  |
| swoft-task | 官方 |  |
| swoft-service-governance | 官方 |  |
| swoft-session | 官方 |  |
| swoft-console | 官方 |  |
| swoft-cache | 官方 |  |
| swoft-log | 官方 |  |
| swoft-memory | 官方 |  |
| swoft-redis | 官方 |  |
| swoft-i18n | 官方 |  |
| swoft-pipeline | 官方 |  |
| swoft-dev-tool | 官方 |  |
| swoft-trace | 官方 |  |
| swoft-auth | 官方 |  |
| swoft-swagger | 官方 |  |

## 框架生命周期

## 请求生命周期
每一个请求的开始到结束，都是由 Swoole 本身的 `onRequest()` 或 `onReceive()` 事件监听并委托给 `Dispatcher` 来处理并响应的，而 `Dispatcher` 的主要职责是调度请求生命周期内的各个参与者(组件)。  

在 `HTTP Server` 的情况下，将由 `ServerDispatcher` 来负责调度，参与者主要包括 `RequestContext`, `ExceptionHandler`, `RequestHandler` ，`RequestContext(请求上下文)` 作为当前的请求信息的容器将贯穿整个请求生命周期，负责信息的储存和传递；`ExceptionHandler(异常处理器)` 则是在遇到异常的情况下出来收拾场面的，确保在各种异常情况下依旧能给客户端返回一个预期内的结果；而 `RequestHandler(请求处理器)` 则是整个请求生命周期的核心组件，其实内部也就是 `Middleware(中间件)` ，该组件实现了 `[PSR-15](https://www.php-fig.org/psr/psr-15/)` 协议，负责将 Request -\> Router -\> Controller -\> Action -\> Renderer -\> Response 这整一个请求流程贯穿起来，下面是一张请求生命周期的流程示意图。  
![RequestLifecycle](../images/request-lifecycle.png)  
在 `RPC Server` 的情况下，与 `HTTP Server` 类似，区别在于参与者只有 `RequestContext` 和 `RequestHandler`，而 `RequestHandler`  内的 `Middleware` 也和 `HTTP Server` 的类似，比较明显的区别在于 `RPC Server` 下会有一个 `ServicePacker(数据打包器)` 来负责将服务方法或异常返回的数据打包成一个统一的数据格式，并返回给客户端。

## 注解