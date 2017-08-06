# RPC服务

RPC及内部服务通过监听TCP端口实现，通过swoft.ini日志配置TCP监听端口信息。

```python
[tcp]
#是否开启TCP端口监听
enable = 1
#监听host
host = "0.0.0.0"
#监听端口
port = 8099
#TCP类型
type = SWOOLE_SOCK_TCP
```

## RPC服务组成

RPC服务由三大部分组成

1. 连接池
2. 熔断器
3. 数据包传输格式

## RPC服务配置

如下配置一个用user\(用户\)服务

```php
return [
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
];
```

## RPC使用

RPC使用很久简单，第一步定义RPC服务函数，第二步调用使用

定义RPC函数,注意定义的服务名称XXXService且继承至InnerService，服务函数和正常定义函数一样的。RPC定义服务一般放在controllers/services/目录下面

```php
namespace app\controllers\services;

use swoft\web\InnerService;

/**
 * RPC服务函数
 *
 * @uses      UserService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserService extends InnerService
{
    public function getUserInfo(...$uids)
    {
        $user = [
            'name' => 'boby',
            'desc' => 'this is boby'
        ];

        $data = [];
        foreach ($uids as $uid){

            $user['uid'] = $uid;
            $data[] = $user;
        }

        return $data;
    }
}
```

使用demo

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



