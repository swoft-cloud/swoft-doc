# 发布、订阅


## 发布 publish

描述: 将消息发布到频道

所需参数
- channel: 要发布到的频道
- message: 消息

例子
```php
Redis::publish('chan-1', 'hello, world!');  // 发送消息
Redis::publish('chan-2', 'hello, world2!'); // 发送消息
```

## 订阅 subscribe

描述: 订阅频道。

所需参数

- channels: 多个通道名称需要是一个数组
- callback: 回调函数接收3个参数。
   - redis redis实例
   - chan 通道名称
   - msg 接受到的消息(mixed)
   
例子：
```php
function f($redis, $chan, $msg) {
	switch($chan) {
		case 'chan-1':
			...
			break;

		case 'chan-2':
			...
			break;

		case 'chan-2':
			...
			break;
	}
}
// 订阅 3 个 频道
Redis::subscribe(['chan-1', 'chan-2', 'chan-3'], 'f'); 
```

## 按匹配模式订阅频道 pSubscribe

使用 psubscribe 方法可以订阅通配符频道，可以用来在所有频道上获取所有消息。

参数
- patterns 匹配规则数组
- callback 接受到消息回调的闭包
    - redis redis 实例
    - pattern 匹配的规则
    - chan 通道名称
    - msg 接收到的消息(mixed)
    
例子：    
```php
Redis::psubscribe(['*'], function ($redis, $pattern, $chan, $msg) {
    echo "Pattern: $pattern\n";
    echo "Channel: $chan\n";
    echo "Payload: $msg\n";
});

Redis::psubscribe(['users.*'], function ($redis, $pattern, $chan, $msg) {
   echo "Pattern: $pattern\n";
   echo "Channel: $chan\n";
   echo "Payload: $msg\n";
});
```


