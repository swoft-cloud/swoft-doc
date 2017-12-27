# RPC服务

RPC 及内部服务通过监听TCP端口实现，通过 `.env` 日志配置 TCP 监听端口信息。

```dotenv
# Server
PFILE=/tmp/swoft.pid
PNAME=php-swoft
# 是否开启RPC
TCPABLE=true

# TCP
TCP_HOST=0.0.0.0
TCP_PORT=8099
TCP_MODEL=SWOOLE_PROCESS
TCP_TYPE=SWOOLE_SOCK_TCP
TCP_PACKAGE_MAX_LENGTH=2048
TCP_OPEN_EOF_CHECK=false
```

## RPC 服务组成

RPC 服务由三大部分组成

1. 连接池
2. 熔断器
3. 数据包传输格式

## RPC 服务

使用RPC之前首先要定义一个RPC连接池，连接池配置支持properties和.env两种方式

### 定义配置连接池

首先定义一个类，继承PropertyPoolConfig连接池配置对象类，定义需要配置的属性

```php
/**
 * the config of service user
 *
 * @Bean()
 * @uses      UserPoolConfig
 * @version   2017年12月16日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserPoolConfig extends PropertyPoolConfig
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

### 定义连接池

定义一个类继承ServicePool，并注入连接池配置对象

```php
/**
 * the pool of user service
 *
 * @Pool(name="user")
 *
 * @uses      UserServicePool
 * @version   2017年12月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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

### 配置连接池

缓存连接池支持两种方式配置，properties或.env环境文件中配置。如果两者都有，.env配置会覆盖properties里面配置

#### properties配置

配置文件路径config/properties/service.php

```php
return [
    'user' => [
        // ...
    ],
];
```

#### .env配置

.env格式，可以从.env.example里面复制，再修改成配置信息。

```php
# the pool of user service
USER_POOL_NAME=user
USER_POOL_URI=127.0.0.1:8099,127.0.0.1:8099
USER_POOL_MAX_IDEL=6
USER_POOL_MAX_ACTIVE=10
USER_POOL_MAX_WAIT=20
USER_POOL_TIMEOUT=200
USER_POOL_USE_PROVIDER=false
USER_POOL_BALANCER=random
USER_POOL_PROVIDER=consul
```

## 熔断器

### 定义熔断器

```php

/**
 * the breaker of user
 *
 * @Breaker("user")
 * @uses      UserBreaker
 * @version   2017年12月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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

### 熔断器配置

配置和连接池配置一样支持两种方式

#### properties配置

配置文件路径config/properties/breaker.php

```php
return [
    'user' => [
        // ...
    ],
];
```

#### .env配置

.env格式，可以从.env.example里面复制，再修改成配置信息。

```php
## the breaker of user service
 USER_BREAKER_FAIL_COUNT = 3
 USER_BREAKER_SUCCESS_COUNT = 6
 USER_BREAKER_DELAY_TIME = 5000
```



## RPC 使用

RPC连接池和熔断器都是通过user名称关联在一起的，一定要保证名称一致。RPC 使用很简单，只需使用@Service注解即可定义一个服务。第一步定义 RPC 服务函数，第二步调用

### @Service

- @Service() 定义服务名称，可以给服务取一个别名
- @Service() 如果未定义别名，自动解析类名前缀。

### @Mapping

- @Mapping() 给函数方法名，取一个别名(映射名称)。
- @Mapping() 如果未定义别名，自动解析函数名称

### 使用实例

```php
/**
 * 用户service
 *
 * @Service()
 * @uses      UserService
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserService
{
    /**
     * 逻辑层
     *
     * @Inject()
     * @var UserLogic
     */
    private $userLogic;

    /**
     * 用户信息
     *
     * @Mapping("getUserInfo")
     * @param array ...$uids
     *
     * @return array
     */
    public function getUserInfo(...$uids)
    {
        return $this->userLogic->getUserInfo($uids);
    }

    /**
     * @Mapping("getUser")
     * @Enum(name="type", values={1,2,3})
     * @Number(name="uid", min=1, max=10)
     * @Strings(name="name", min=2, max=5)
     * @Floats(name="price", min=1.2, max=1.9)
     *
     * @param int    $type
     * @param int    $uid
     * @param string $name
     * @param float  $price
     * @param string $desc  default value
     * @return array
     */
    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
    {
        return [$type, $uid, $name, $price, $desc];
    }

    /**
     * 未使用注解，默认方法名
     *
     * @return array
     */
    public function getUserList()
    {
        return ['uid1', 'uid2'];
    }
}
```

使用 Demo，调用格式 `service::method`

```php
// 直接调用
$result = Service::call("user", 'User::getUserInfo', [2,6,8]);

//并发调用
$res = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
$res2 = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
$users = $res->getResult();
$users2 = $res2->getResult();


$deferRet = $users;
$deferRet2 = $users2;
```



