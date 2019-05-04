# Redis 事务操作

redis 事务不能回滚，但是能保证原子性，用 `lua` 脚本也能实现 redis 事务效果

下面是一个事务操作例子，返回数据有点特别，偶数为成功与否，奇数为执行 `key`，下面写了一个结果遍历方法
```php
$count  = 2;
$result = Redis::transaction(function (\Redis $redis) use ($count) {
    for ($i = 0; $i < $count; $i++) {
        $key = "key:$i";
        $redis->set($key, $i);
        $redis->get($key);
    }
});

/*
$result = array(4) {
    [0]=>
    bool(true)
    [1]=>
    int(0)
    [2]=>
    bool(true)
    [3]=>
    int(1)
}*/

foreach ($result as $index => $value) {
    if ($index % 2 == 0) {
        // is exec ok ？
       if ($value) {
            // todo... 
       }
    }
}

```