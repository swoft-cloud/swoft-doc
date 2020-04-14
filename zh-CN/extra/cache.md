# 缓存组件

swoft 2.0 重新封装了基础的缓存组件

> github - https://github.com/swoft-cloud/swoft-cache

内置支持的多种方式的存储驱动处理：

- 本机单文件方式存储处理
- 本机单文件方式存储处理，但是使用swoole的协程方法读写内容
- 本机多文件方式存储处理
- 本机多文件方式存储处理，但是使用swoole的协程方法读写内容
- 使用 `swoole table` 存储数据, 随服务启动，跨进程存储
- 使用 `redis` 存储数据，依赖 `swoft-redis` 组件
- php内存驱动处理器，使用php array存储数据。_用于测试，数据仅在当前进程保存_

## 安装组件

swoft-cache 作为一个额外的功能组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/cache
```

- 通过 composer.json 配置:

```json
    "swoft/cache": "~2.0.0"
```

## 配置组件

下面是缓存组件的默认配置 （在类文件 `Swoft\Cache\AutoLoader` 中），你可以参照他们在 `bean.php` 中进行覆盖设置

```php
[
    // 缓存管理类
    Cache::MANAGER    => [
        'class'   => CacheManager::class,
        'adapter' => bean(Cache::ADAPTER),
    ],
    // 缓存适配器配置
    Cache::ADAPTER    => [
        'class'      => MultiFileAdapter::class,
        'serializer' => bean(Cache::SERIALIZER),
        // savePath for file cache adapter
        'savePath'   => alias('@runtime/caches'),
        // dataFile for swoole table cache adapter
        // 'dataFile'   => alias('@runtime/caches/cache.data'),
    ],
    // 数据序列化配置
    Cache::SERIALIZER => [
        'class' => PhpSerializer::class
    ]
];
 ```

### 配置redis驱动

```php
use Swoft\Cache\Adapter\RedisAdapter;


    Cache::ADAPTER => [
        'class'    => RedisAdapter::class,
        // set redis pool
        'redis' => bean('redis.pool'),
        // 'prefix' => 'swoft_cache:',
    ],
```


## 使用代码示例


内置提供了 `Swoft\Cache\Cache` 类来快速使用缓存功能操作: `get/set/delete`

```php
<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use InvalidArgumentException;
use Swoft\Cache\Cache;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class CacheController
 *
 * @since 2.0.8
 *
 * @Controller()
 */
class CacheController
{
    /**
     * @RequestMapping()
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set(): array
    {
        $ok  = Cache::set('ckey', 'cache value');
        $ok1 = Cache::set('ckey1', 'cache value2', 5);

        return ['ckey' => $ok, 'ckey1' => $ok1];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(): array
    {
        $val = Cache::get('ckey');

        return [
            'ckey'  => $val,
            'ckey1' => Cache::get('ckey1')
        ];
    }

    /**
     * @RequestMapping("del")
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function del(): array
    {
        /** @var Response $resp */
        // $resp = context()->getResponse();

        return ['del' => Cache::delete('ckey')];
    }
}
```

更多的操作方法，请获取 `cacheManager` bean 对象使用：

> 下面的几种方式都可以获取到`cacheManager` bean对象

```php
/** @var Swoft\Cache\CacheManager $manager */
$manager = bean('cacheManager');
$manager = bean(Cache::MANAGER);
$manager = Cache::manager();

```

使用：

```php
/** @var Swoft\Cache\CacheManager $manager */
$manager->set('key', 'value', 30);
$value = $manager->get('key');
$manager->delete('key');

$manager->getMultiple(['key', 'key1']);
```





