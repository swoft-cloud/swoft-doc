# RPC服务

RPC及内部服务通过监听TCP端口实现，通过swoft.ini日志配置TCP监听端口信息。

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

## RPC服务配置

如下配置一个用user\(用户\)服务

```php
return [

    // ...

    // RCP打包、解包
    "packer"          => [
        'class' => JsonPacker::class
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

## RPC使用

RPC使用很久简单，第一步定义RPC服务函数，第二步调用使用

> @Service() 注解定义服务名称，可以给服务区一个别名
>
> @Mapping() 注解，方法名映射。
>
> 服务类需要继承 \Swoft\Web\InnerService



```php
<?php

namespace App\Services;

use App\Models\Logic\UserLogic;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Mapping;
use Swoft\Bean\Annotation\Service;
use Swoft\Web\InnerService;

/**
 * 用户service
 *
 * @Service()
 */
class UserService extends InnerService
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

使用demo，调用格式"service::method"

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



