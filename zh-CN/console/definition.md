# 定义命令
命令的定义主要通过@Comamnd和@Mapping两个注解，@Command 定义命令组名称，@Mapping 定义操作命令的映射关系。

## 注解

**@Command**

定义命令组

- name 参数，定义命令组名称，如果缺省，根据配置后缀自动解析
- coroutine 参数，定义命令是否为协程，默认 false,如果为true，框架会启动一个协程运行改命令

**@Mapping**    

定义操作命令映射关系

- name 参数，定义操作命令的一个映射名称，如果缺省，会执行使用方法名称。

## 帮助信息

命令帮助信息是命令使用说明信息，也是通过注解完成定义。

- 类描述，对应命令组信息描述
- 方法描述，对应该执行命令的信息描述
- @Usage 定义使用命令格式
- @Options 定义命令选项参数
- @Arguments 定义命令参数
- @Example 命令使用例子

## 实例

```php
/**
 * Test command
 *
 * @Command(coroutine=true)
 */
class TestCommand
{
    /**
     * this test command
     *
     * @Usage
     * test:{command} [arguments] [options]
     *
     * @Options
     * -o,--o this is command option
     *
     * @Arguments
     * arg this is argument
     *
     * @Example
     * php swoft test:test arg=stelin -o opt
     *
     * @param Input  $input
     * @param Output $output
     *
     * @Mapping("test2")
     */
    public function test(Input $input, Output $output)
    {
        App::error('this is eror');
        App::trace('this is trace');
        Coroutine::create(function (){
            App::error('this is eror child');
            App::trace('this is trace child');
        });

        var_dump('test', $input, $output, Coroutine::id(),Coroutine::tid());
    }

    /**
     * this demo command
     *
     * @Usage
     * test:{command} [arguments] [options]
     *
     * @Options
     * -o,--o this is command option
     *
     * @Arguments
     * arg this is argument
     *
     * @Example
     * php swoft test:demo arg=stelin -o opt
     *
     * @Mapping()
     */
    public function demo()
    {
        $hasOpt = input()->hasOpt('o');
        $opt    = input()->getOpt('o');
        $name   = input()->getArg('arg', 'swoft');

        App::trace('this is command log');
        Log::info('this is comamnd info log');
        /* @var UserLogic $logic */
        $logic = App::getBean(UserLogic::class);
        $data  = $logic->getUserInfo(['uid1']);
        var_dump($hasOpt, $opt, $name, $data);
    }
}
```

> 命令逻辑里面可以使用 Swoft 所有功能，唯一不一样的是，如果命令不是协程模式运行，所有IO操作，框架底层会自动切换成传统的同步阻塞，但是使用方法是一样的。
