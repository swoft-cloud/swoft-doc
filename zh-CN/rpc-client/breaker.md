# 熔断器

熔断器概念，请详看服务治理章节。

## 定义熔断器

继承CircuitBreaker类，配置属性信息。一个服务的熔断器名称要和连接池一致。

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