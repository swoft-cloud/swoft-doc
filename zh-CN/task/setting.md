# 配置与启用

任务配置参数，可以直接在对应的 `Server->setting` 配置即可，如果要启用任务更简单，`Server` 新增一个 `on` 事件。

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