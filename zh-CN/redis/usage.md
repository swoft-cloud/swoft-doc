# Redis 简单使用

## 简单操作
 比如显示给定用户的配置文件。
```php
  $user = Redis::get('user:profile:'.$id);
  
  Redis::set('user:profile:'.$id, "ok");
 ```
 你可以在 `Redis` 上调用任何的 `Redis` 命令。`Swoft` 使用魔术方法将传递命令给 `Redis`服务器，因此只需传递 Redis 命令所需的参数即可：
 
 ```php
  Redis::set('name', 'Taylor');
  
  $values = Redis::lrange('names', 5, 10);
 ```
所有方法和 操作 `phpredis` 原生一样 本质是通过魔术方法代理访问的，让操作变得更简单


## 指定连接池获取连接
当然 所有的连接都是在连接池中分配的 如果你想使用自己定义的连接可以使用 

```php
$poolName  = 'redis-clusters-pool'
$redis     =  Redis::connection($poolName);
$redis->get("a");
```
 获取的连接了之后和 操作 和`phpredis` 原生使用方式扩展一致 如何创建连接池 参考 `redis 设置`章节
 ，默认是在 `redis.pool`连接池中获取的