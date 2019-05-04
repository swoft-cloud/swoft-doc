# redis 管道操作

如果你需要在一个操作中向服务器发送很多命令，推荐你使用管道命令。 pipeline 方法接收一个带有 Redis 实例的 闭包 。 你可以将所有的命令发送给这个 Redis 实例，它们都会一次过执行完：

比如连续设置 10 个key，返回的是可以数组，你可以遍历判断是否全部成功：
```php
public function testPipeline()
{
    $count  = 10;
    $result = Redis::pipeline(function (\Redis $redis) use ($count) {
        for ($i = 0; $i < $count; $i++) {
            $redis->set("key:$i", $i);
        }
    });

  // \count($result) ==  $count;

    foreach ($result as $index => $value) {
      //  $index 第几个
      //  $value == true 或者 $value == false
    }
}
```
