# 生命周期

## 框架生命周期

## 请求生命周期
每一个请求的开始到结束，都是由 Swoole 本身的 `onRequest()` 或 `onReceive()` 事件监听并委托给 `Dispatcher` 来处理并响应的，而 `Dispatcher` 的主要职责是调度请求生命周期内的各个参与者(组件)。  

在 `HTTP Server` 的情况下，将由 `ServerDispatcher` 来负责调度，参与者主要包括 `RequestContext`, `ExceptionHandler`, `RequestHandler` ，`RequestContext(请求上下文)` 作为当前的请求信息的容器将贯穿整个请求生命周期，负责信息的储存和传递；`ExceptionHandler(异常处理器)` 则是在遇到异常的情况下出来收拾场面的，确保在各种异常情况下依旧能给客户端返回一个预期内的结果；而 `RequestHandler(请求处理器)` 则是整个请求生命周期的核心组件，其实内部也就是 `Middleware(中间件)` ，该组件实现了 `[PSR-15](https://www.php-fig.org/psr/psr-15/)` 协议，负责将 Request -\> Router -\> Controller -\> Action -\> Renderer -\> Response 这整一个请求流程贯穿起来，下面是一张请求生命周期的流程示意图。  
![RequestLifecycle](../images/request-lifecycle.png)  
在 `RPC Server` 的情况下，与 `HTTP Server` 类似，区别在于参与者只有 `RequestContext` 和 `RequestHandler`，而 `RequestHandler`  内的 `Middleware` 也和 `HTTP Server` 的类似，比较明显的区别在于 `RPC Server` 下会有一个 `ServicePacker(数据打包器)` 来负责将服务方法或异常返回的数据打包成一个统一的数据格式，并返回给客户端。