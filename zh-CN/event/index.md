# 事件管理

Swoft 2 事件进行了更加清晰和严谨的规划。提供了基本的事件注册与触发管理。

## 功能说明

- implement the [Psr 14](https://github.com/php-fig/fig-standards/blob/master/proposed/event-dispatcher.md) - Event dispatcher
- 支持通过注解快速的注册事件，事件组
- 支持设置事件优先级
- 支持对通配符事件的监听

> 作为Swoft的核心组件，事件管理会自动启用

## 安装

```bash
composer require swoft/event
```

## Git仓库

- Github https://github.com/swoft-cloud/swoft-event

## 参与贡献

欢迎参与贡献，您可以

- fork 我们的开发仓库 [swoft/component](https://github.com/swoft-cloud/swoft-component)
- 修改代码然后发起 PR
- 关于发起PR的[注意事项](https://github.com/swoft-cloud/swoft/issues/829)

## swoft里的事件

Swoft 2 事件进行了更加清晰和严谨的规划。提供了丰富的事件，以便于开发者使用。

在swoft我们将事件分为三大类：

- swoole server的回调事件
- swoft server的事件，基于swoole的回调处理，扩展了一些可用事件以增强自定义性
- 应用级别内的自定义事件管理和使用

## 相关介绍

一些关于自定义事件的拓展介绍说明

### 事件分组

除了一些特殊的事件外，在一个应用中，大多数事件是有关联的，此时我们就可以对事件进行分组，方便识别和管理使用。

- **事件分组**  推荐将相关的事件，在名称设计上进行分组

例如：

```text
swoft.server.*
swoft.process.*
swoft.pool.*

swoft.http.request.before
swoft.http.request.after

swoft.db.query.start
swoft.db.query.after

swoft.redis.start
swoft.redis.after

swoft.ws.start
swoft.ws.after

swoft.tcp.start
swoft.tcp.after

swoft.udp.start
swoft.udp.after
```

### 事件通配符 `*`

支持使用事件通配符 `*` 对一组相关的事件进行监听, 分两种。

1. `*` 全局的事件通配符。直接对 `*` 添加监听器(`@Listener("*")`), 此时所有触发的事件都会被此监听器接收到。
2. `{prefix}.*` 指定分组事件的监听。
  - 例如 `@Listener("swoft.db.*")`, 此时所有触发的以 `swoft.db.` 为前缀的事件(eg `swoft.db.query` `swoft.db.connect`)都会被此监听器接收到。

> 当然，你在事件到达监听器前停止了本次事件的传播`$event->stopPropagation(true)`，就不会被后面的监听器接收到了。

### 更多介绍

更多关于自定义事件的理解参考 [https://github.com/inhere/php-event-manager/blob/master/README.md](https://github.com/inhere/php-event-manager/blob/master/README.md)
