# 输入输出

输入输出指的是，获取用户命令参数和显示信息到控制台。命令逻辑里面，可以通过函数参数和全局函数获取输入输出对象。

## 函数参数

如果需要使用输入和输出对象，可以再操作命令函数上，定义输入和输出对象，底层框架会自动注入对象。

```php
/**
 * Test command
 *
 * @Command(coroutine=true)
 */
class TestCommand
{
    /**
     * ......
     *
     * @param Input  $input
     * @param Output $output
     *
     * @CommandMapping("test2")
     */
    public function test(Input $input, Output $output)
    {
        // ......
    }
}
```

## 全局函数

```php
/**
 * Test command
 *
 * @Command(coroutine=true)
 */
class TestCommand
{
    /**
     * @CommandMapping()
     */
    public function demo()
    {
        $input = \input();
        $output = \output();
        
        // ......
    }
}
```