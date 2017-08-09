

# 应用结构

src目录结构如下:

```
├── App.php // 应用简写管理类
├── base // 基础的类
│   ├── ApplicationContext.php
│   ├── Application.php
│   ├── Config.php
│   ├── Controller.php
│   ├── RequestContext.php
│   ├── Request.php
│   ├── Response.php
│   └── Timer.php
├── cache // 缓存
│   ├── RedisClient.php
│   └── RedisResult.php
├── circuit // 熔断器
│   ├── CircuitBreaker.php
│   ├── CircuitBreakerState.php
│   ├── CloseState.php
│   ├── HalfOpenState.php
│   └── OpenState.php
├── composer.json
├── console // 命令行
│   ├── Application.php
│   ├── Command.php
│   ├── Controller.php
│   └── controllers
│       └── ServerController.php
├── db // 预留mysql数据库ORM
├── di // 容器注入及管理
│   ├── BeanFactoryInterface.php
│   └── BeanFactory.php
├── exception // 异常
│   ├── MysqlException.php
│   ├── RedisException.php
│   └── ServiceException.php
├── filter // 过滤器
│   ├── ExactUriPattern.php
│   ├── ExtUriPattern.php
│   ├── FilterChain.php
│   ├── Filter.php
│   ├── IFilter.php
│   ├── IUriPattern.php
│   └── PathUriPattern.php
├── helpers // 公共工具类
│   ├── ArrayHelper.php
│   └── ResponseHelper.php
├── http // httpClient
│   ├── HttpClient.php
│   └── HttpResult.php
├── log // 日志
│   ├── FileHandler.php
│   └── Logger.php
├── pool // 连接池
│   ├── balancer
│   │   ├── IBalancer.php
│   │   ├── RandomBalancer.php
│   │   └── RoundRobinBalancer.php
│   ├── ConnectPool.php
│   ├── Pool.php
│   ├── RedisPool.php
│   └── ServicePool.php
├── README.md
├── service // RPC服务
│   ├── ConsulProvider.php
│   ├── IPack.php
│   ├── JsonPacker.php
│   ├── Service.php
│   ├── ServiceProvider.php
│   └── ServiceResult.php
└── web // 核心处理类
    ├── AbstractResult.php
    ├── Application.php
    ├── Controller.php
    ├── DispatcherInterface.php
    ├── Dispatcher.php
    ├── ErrorHandler.php
    ├── InnerService.php
    ├── IResult.php
    ├── Request.php
    ├── Response.php
    ├── RouterInterface.php
    └── Router.php
```



