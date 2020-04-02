# 定时器

对应一些延迟执行或者周期性执行的任务，此时需要使用定时器实现。Swoft 框架中封装了一套定时器操作方式，开发者可以直接使用，切记一定要使用 Swoft 框架封装的，如果直接使用 Swoole 提供的会因为上下文，带来一系列问题。

<p class="tip"> 2.0.5+ 支持，定时器一定要使用 Swoft 框架封装的，不要直接使用 Swoole 提供的。 </p>

## Tick

周期性的执行一项任务，使用例子如下:

```php
$paramOne = 1;
$paramTwo = 1;
\Swoft\Timer::tick(1, function ($paramOne, $paramTwo) {
    // To to something
}, $paramOne, $paramTwo);
```

`\Swoft\Timer::tick` 详细参数：

- $msec 周期执行的时间，单位是毫秒
- $callback 回调函数
- $params 传递的参数，会原封不动的传递给回调函数。

## After

延迟一段时间执行任务，使用例子如下：

```php
$paramOne = 1;
$paramTwo = 1;
\Swoft\Timer::after(1, function ($paramOne, $paramTwo) {
    // To to something
}, $paramOne, $paramTwo);
```

- $msec 延迟时间，单位是毫秒
- $callback 回调函数
- $params 传递的参数，会原封不动的传递给回调函数。