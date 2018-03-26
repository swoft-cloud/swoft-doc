# WebSocket 服务

WebSocket 服务基于现有http server上的简单实现。即开启websocket服务的同时可以处理http请求(_是允许配置禁用的_)。

## 生命周期

### 连接

一个连接的生命周期是从握手开始，直到关闭连接

```text
request -> handeshake -> [...Communication...] -> close
```

> 直到关闭前，你都能获取到连接的基本信息(request, client info)

### 消息请求

一次消息请求的生命周期：

> 上面的 `[...Communication...]` 中可能包含了无数次的消息往返

```text
received -> handle [ -> send]
```

