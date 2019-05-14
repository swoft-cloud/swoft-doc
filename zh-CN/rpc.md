# RPC 组件

## RPC 基础

这里有张很好的图, 以比较复杂的 grpc 来讲解整个 rpc 的流程:

![grpc 详解](http://qiniu.daydaygo.top/20190506225758.png)

RPC, 远程过程调用, 像 **访问本地函数一样通过网络调用远程提供的服务**

从网络通信层面来理解 RPC, 标准的 tcp 通信:
- tcp server
- tcp client
- 自定义 tcp 通信协议, 为什么需要协议, 请参考这篇 [网络通信协议设计](https://wiki.swoole.com/wiki/page/484.html)

> 这么简单? 是的, 就是这么简单.

那为什么还会有 grpc/tars 等各种不同的 rpc 方案呢? 大家其实都是在 **协议设计(包括序列化) + 自动化(IDL, 代码自动生成)** 上下功夫.

## swoft 中 rpc 实现

对应上面的三部分, swoft 的 rpc 实现包含 3 个组件:
- swoft/rpc: 定义 parse, 用来完成协议的解析
- swoft/rpc-client: rpc client, 基于 swoole 提供的协程 client
- swoft/rpc-server: rpc server, 基于 `swoft/server`, 基于 swoole 提供的协程 server

以 swoft 提供的 demo 为例:

- 配置 rpc server:

```php
// app/bean.php
'rpcServer'  => [
    'class' => ServiceServer::class,
],
```

- 书写 rpc server 业务代码

```
root@cf880ad548d9 /d/s/swoft-skeleton# tree app/Rpc/
app/Rpc/
├── Lib
│   └── UserInterface.php // 使用 Interface 来抽象协议部分, rpc server 和 rpc client 实现此接口来实现协议
├── Middleware
│   └── ServiceMiddleware.php // 按需添加中间件, 提供 rpc server 可扩展性
└── Service // rpc server 业务代码部分
    ├── UserService.php
    ├── UserServiceV2.php
    └── big.data
```

- 配置 rpc client:

```php
// app/bean.php
'user'       => [ // rpc client
    'class'   => ServiceClient::class,
    'host'    => '127.0.0.1',
    'port'    => 18307,
    'setting' => [
        'timeout'         => 0.5,
        'connect_timeout' => 1.0,
        'write_timeout'   => 10.0,
        'read_timeout'    => 0.5,
    ],
    'packet'  => \bean('rpcClientPacket')
],
'user.pool'  => [ // rpc client 配置到连接池中
    'class'  => ServicePool::class,
    'client' => \bean('user')
],
```

rpc-client 配置了连接池, 协程 client 需要使用连接池(**连接池是标配**), 请参考这篇 [是否可以共用同一个redis/mysql连接](https://wiki.swoole.com/wiki/page/325.html)

至于怎么使用连接池, 上面的配置就够了, 是的, 还是这么简单.

- 书写 rpc client 业务代码:

`app/Http/Controller/RpcController.php` 下提供了完整的示例, 但核心代码其实只有几行:

```php
// 注解 + 依赖注入, 从连接池中获取 rpc client
// 变量类型设置为 UserInterface, 和 rpc server 一致
/**
* @Reference(pool="user.pool")
*
* @var UserInterface
*/
private $userService;

// 传统实现方式, 对比来看, 就能感受到 注解 的优雅
public function __construct() {
    $this->$userService = new xxx();
}

// 调用 rpc server
$result  = $this->userService->getList(12, 'type');
$result2 = $this->userService2->getList(12, 'type');
```

rpc 的简单使用, 到这里就结束了. 下面我们已 grpc 为例, 来看看更高阶一点的用法.

## grpc 基础

关于 grpc 的基础, 推荐参看这篇 [tech| 再探grpc](https://www.jianshu.com/p/f3221df39e6f)

要注意 2 个点:
- grpc 是基于 http2 协议进行通信的, 底层需要使用 `\Swoole\Http\Server`
- 并不需要完整的代码生成, 只要解决 protobuf 的序列化/反序列, swoft 框架提供了 grpc 协议解析和网络通信能力

完整过程, 请回顾上图 **grpc 详解**

## swoft 中使用 grpc