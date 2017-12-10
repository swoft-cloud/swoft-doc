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

## RPC 服务配置

如下配置一个用 User\(用户\)服务

```php
return [

    // ...
    // RCP打包、解包
    'servicePacker'     => [
            'class' => \Swoft\Service\ServicePacker::class,
            'type'  => 'json',
    ],
    // 服务发现bean, 目前系统支持consul,只行实现
    'consulProvider'       => [
        'class' => \swoft\service\ConsulProvider::class
    ],

    // user服务连接池
    "userPool"            => [
        "class"           => \swoft\pool\ServicePool::class,
        "uri"             => '127.0.0.1:8099,127.0.0.1:8099', // useProvider为false时，从这里识别配置
        "maxIdel"         => 6,// 最大空闲连接数
        "maxActive"       => 10,// 最大活跃连接数
        "maxWait"         => 20,// 最大的等待连接数
        "timeout"         => '${config.service.user.timeout}',// 引用properties.php配置值
        "balancer"        => '${randomBalancer}',// 连接创建负载
        "serviceName"     => 'user',// 服务名称，对应连接池的名称格式必须为xxxPool/xxxBreaker
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}' // useProvider为true使用，用于发现服务
    ],
    // user服务熔断器
    "userBreaker" => [
        'class'           => \swoft\circuit\CircuitBreaker::class,
        'delaySwithTimer' => 8000
    ],

    // ...

];
```

## RPC 使用

RPC 使用很简单，只需使用@Service注解即可定义一个服务。第一步定义 RPC 服务函数，第二步调用

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



