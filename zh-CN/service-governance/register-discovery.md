# 服务注册

RPC 启动的时候，可以配置是否启动的时候注册到第三方比如 consul/etcd ，使用服务也可通过第三方获取。

## 初始化配置
`.env` 中配置是否启动注册 RPC服务，默认`false`，如果配置`true`，必须配置服务提供方

```
AUTO_REGISTER=false
```

## 服务提供方

通常情况可以不用配置，系统已经配置了默认值

配置文件：`./config/beans/base.php`

```
return [
    // ......
    'providerSelector' => [
        'class' => \Swoft\Sg\ProviderSelector::class,
        'provider' => 'consul',
        'providers' => [
            'consul' => \Swoft\Sg\Provider\ConsulProvider::class
        ]
    ],
    // ......
];

- provider 配置服务提供方，默认consul
- providers 配置自定义服务，通过KEY名称使用
```
### Consul服务方配置

Consul 服务提供方，在`.env`和`./config/properties/provider.php`都可以配置，但是`env`会覆盖`properties`

**env**    

```
# Consul
CONSUL_ADDRESS=http://127.0.0.1
CONSUL_PORT=8500
CONSUL_REGISTER_NAME=user
CONSUL_REGISTER_ETO=false
CONSUL_REGISTER_SERVICE_ADDRESS=127.0.0.1
CONSUL_REGISTER_SERVICE_PORT=8099
CONSUL_REGISTER_CHECK_NAME=user
CONSUL_REGISTER_CHECK_TCP=127.0.0.1:8099
CONSUL_REGISTER_CHECK_INTERVAL=10
CONSUL_REGISTER_CHECK_TIMEOUT=1
```

**properties**    

```php
return [
    'consul' => [
        'address' => '',
        'port'    => 8500,
        'register' => [
            'id'                => '',
            'name'              => '',
            'tags'              => [],
            'enableTagOverride' => false,
            'service'           => [
                'address' => 'localhost',
                'port'   => '8099',
            ],
            'check'             => [
                'id'       => '',
                'name'     => '',
                'tcp'      => 'localhost:8099',
                'interval' => 10,
                'timeout'  => 1,
            ],
        ],
        'discovery' => [
            'name' => 'user',
            'dc' => 'dc',
            'near' => '',
            'tag' =>'',
            'passing' => true
        ]
    ],
];

```

- 参数具体含义，参考 Consul 相关文档




