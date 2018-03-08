# HTTP服务

基于 `\Swoole\Http\Server` 实现的协程HTTP服务, 框架层做了很好的封装, 用户按照传统的 MVC 方式编写代码, 就能获得协程带来的超高性能.

## 请求生命周期

请参考 [框架核心 - 生命周期](https://doc.swoft.org/beta/zh-CN/core/framework.html). 了解 **请求生命周期**, 有利于理解HTTP服务各组件, 编写出更好代码.

## HTTP服务组件

- 控制器: Controller, MVC中的C
- 路由: Router, 将 url 配置到 Controller 中的 Action
- 中间件: Middleware, 处理一些前置或者后置逻辑
- 验证器: Validtor, 请求中的数据验证
- RESTFUL: restful 风格的HTTP服务
