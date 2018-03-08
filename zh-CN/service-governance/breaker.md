# 熔断器

## 三种状态

熔断器有三种状态，开启、关闭、半开，每种状态都有不同对应的操作，目前内部使用状态模式实现的。

- 关闭状态 正常逻辑处理，当失败次数达到上限可以切换到开启状态
- 开启状态 服务降级处理，一定时间延迟后尝试切换到半开状态
- 半开状态 正常逻辑处理，当业务失败次数达到一定值，切换到开启，当业务成功次数达到一定值，切换到关闭。

## 注解

**@Breaker**    

定义熔断器

- name 熔断器名称，缺省默认是类名

## 定义熔断器

定义熔断器需要继承CircuitBreaker类，配置属性信息，配置信息可以通过@value和@Config两个注解实现，注解详解请看配置章节。

```php
/**
 * the breaker of user
 *
 * @Breaker("user")
 */
class UserBreaker extends CircuitBreaker
{
    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @Value(name="${config.breaker.user.failCount}", env="${USER_BREAKER_FAIL_COUNT}")
     * @var int
     */
    protected $switchToFailCount = 3;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @Value(name="${config.breaker.user.successCount}", env="${USER_BREAKER_SUCCESS_COUNT}")
     * @var int
     */
    protected $switchToSuccessCount = 3;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @Value(name="${config.breaker.user.delayTime}", env="${USER_BREAKER_DELAY_TIME}")
     * @var int
     */
    protected $delaySwitchTimer = 500;
}

```

## RPC使用

RPC中默认是根据@Reference注解指定的服务名称，加载名称相同的熔断器，进行逻辑处理

## 任意使用

可以再任意逻辑中使用熔断器

```php
\breaker('name')->call($handler, $params, $fallback);
```
- $handler 可调用的callback
- $params 调用传递的参数
- $fallback 参考服务降级章节的普通服务降级

