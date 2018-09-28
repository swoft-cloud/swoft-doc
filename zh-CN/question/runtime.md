# 运行问题

- 报错 `Class 'Swoole\Coroutine\Redis' not found`

> 请安装swoole前，先安装 `hiredis`。编译swoole时加上选项 `--enable-async-redis` 
