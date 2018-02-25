# 连接池

每个服务就会有一个个已连接池，根据连接池名称区别是哪个连接池。连接池定义由两部分组成，连接池和连接池配置。

## 连接池配置
连接池配置和之前一样，继承PoolProperties类，通过properties和env方法配置数据，env会覆盖properties。
```php
/**
 * the config of service user
 *
 * @Bean()
 */
class UserPoolConfig extends PoolProperties
{
    /**
     * the name of pool
     *
     * @Value(name="${config.service.user.name}", env="${USER_POOL_NAME}")
     * @var string
     */
    protected $name = "";

    /**
     * the maximum number of idle connections
     *
     * @Value(name="${config.service.user.maxIdel}", env="${USER_POOL_MAX_IDEL}")
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @Value(name="${config.service.user.maxActive}", env="${USER_POOL_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(name="${config.service.user.maxWait}", env="${USER_POOL_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @Value(name="${config.service.user.timeout}", env="${USER_POOL_TIMEOUT}")
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @Value(name="${config.service.user.uri}", env="${USER_POOL_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(name="${config.service.user.useProvider}", env="${USER_POOL_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(name="${config.service.user.balancer}", env="${USER_POOL_BALANCER}")
     * @var string
     */
    protected $balancer = BalancerSelector::TYPE_RANDOM;

    /**
     * the default provider is consul provider
     *
     * @Value(name="${config.service.user.provider}", env="${USER_POOL_PROVIDER}")
     * @var string
     */
    protected $provider = ProviderSelector::TYPE_CONSUL;
}
```

## 连接池配置
每个服务连接池，需要定义一个名称，且和熔断器一样，缺省使用类名。

```php
/**
 * the pool of user service
 *
 * @Pool(name="user")
 */
class UserServicePool extends ServicePool
{
    /**
     * @Inject()
     *
     * @var UserPoolConfig
     */
    protected $poolConfig;
}
```
