# 缓存组件

swoft 2.0 重新封装了基础的缓存组件

> github - https://github.com/swoft-cloud/swoft-cache

内置支持的多种方式的存储驱动处理：

- 本机单文件方式存储处理
- 本机单文件方式存储处理，但是使用swoole的协程方法读写内容
- 本机多文件方式存储处理
- 本机多文件方式存储处理，但是使用swoole的协程方法读写内容
- php内存驱动处理器，使用php array存储数据。_用于测试，数据仅在当前进程保存_
- 使用swoole table 存储数据, 随服务启动，跨进程存储
- 使用redis存储数据，依赖swoft-redis组件


## 配置组件

```php

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            Cache::MANAGER    => [
                'class'   => CacheManager::class,
                'adapter' => bean(Cache::ADAPTER),
            ],
            Cache::ADAPTER    => [
                'class'      => MultiFileAdapter::class,
                'serializer' => bean(Cache::SERIALIZER),
                'savePath'   => alias('@runtime/caches'),
                // 'dataFile'   => alias('@runtime/caches/cache.data'),
            ],
            Cache::SERIALIZER => [
                'class' => PhpSerializer::class
            ]
        ];
    }
 ```
