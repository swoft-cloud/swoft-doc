# 运行问题

- 报错 `Class 'Swoole\Coroutine\Redis' not found`

> 请安装swoole前，先安装 `hiredis`。编译swoole时加上选项 `--enable-async-redis` 

# RequestContext 是否需要手动销毁？

> ```RequestContext(请求上下文)``` 作为当前的请求信息的容器将贯穿整个请求生命周期，负责信息的储存和传递；

通常来说并不需要手动销毁，框架会在**请求结束**后自动销毁`RequestContext`内数据。

但如果你在应用中使用了以下功能，则必须在**请求结束**（**业务代码执行结束**）后手动调用`RequestContext::destroy()`销毁`RequestContext`内数据，否则长时间运行会出现**内存泄漏**问题！！！

- 自定义`TCP server`
- 自定义`UDP server` 
- 自定义`Websocket server`
- 自定义`定时器`
