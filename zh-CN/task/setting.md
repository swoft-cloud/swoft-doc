# 配置与启用

任务配置参数，可以直接在对应的 `Server->setting` 配置即可，如果要启用任务更简单，`Server` 新增一个 `on` 事件。

## 协程任务

Http Server 配置开启任务为例：

```php
return [
    'httpServer' => [
        // ...
        'on'       => [
            SwooleEvent::TASK   => \bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => \bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true
        ]
    ],
]
```

- `task_enable_coroutine` 必须为 `true`
- task 事件和finish 事件必须配置，且为 `TaskListener::class` 和 `FinishListener::class`

Rpc Server 配置开启任务为例：

```php
return [
    'rpcServer' => [
        // ...
        'on'       => [
            SwooleEvent::TASK   => \bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => \bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true
        ]
    ],
]
```

wsServer Server 配置开启任务为例：

```php
return [
    'wsServer' => [
        // ...
        'on'       => [
            SwooleEvent::TASK   => \bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => \bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true
        ]
    ],
]
```

> 任务配置与启用，在 `Http Server` / `Rpc Server` / `Websocket Server` 都完全一样，启用任务需要监听 `task` `finish` 两个事件。

## 同步阻塞任务

Swoft 不仅提供协程任务，并且支持同步任务，同步任务和协程任务只能选择一种运行，两种不能同时存在。同步任务只需配置 `task` 事件，不支持异步 `finish` 事件。官方建议使用协程任务实现业务，
如果需要通过任务实现MongoDB、PostgreSQL 类似这种场景才使用同步任务。

> 2.0.4+ 支持

如下已 Http-server 为例：

```php
return [
    'httpServer' => [
        // ...
        'on'       => [
            SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 6,
            'task_enable_coroutine' => false
        ]
    ],
]
```

- `task_enable_coroutine` 必须设置为 `false`
- task 事件必须是 `SyncTaskListener::class`

<p class="tip"> 同步阻塞任务，不能直接使用框架提供的所有 IO 操作(数据库、缓存、RPC等等)以及应用日志，控制器日志可以使用。 同步阻塞任务的定义和使用与协程任务一直，但是没有上下文。</p>