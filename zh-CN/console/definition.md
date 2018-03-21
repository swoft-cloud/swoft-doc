# 定义命令

命令的定义主要通过@Command和@Mapping两个注解，`@Command` 定义命令组名称，`@Mapping` 定义操作命令的映射关系。

## 注解

**@Command**

定义命令组

- `name` 参数，定义命令组名称，如果缺省，根据配置后缀自动解析
- `enabled` 是否启用，设为不启用时，将不会显示到命令列表。默认 `true`
- `coroutine` 参数，定义命令是否为协程，默认 false,如果为true，框架会启动一个协程运行改命令

**@Mapping**

定义操作命令映射关系

- `name` 参数，定义操作命令的一个映射名称，如果缺省，会执行使用方法名称。

## 帮助信息

命令帮助信息是命令使用说明信息，也是通过注解完成定义。

- 类描述，对应命令组信息描述
- 方法描述，对应该执行命令的信息描述
- `@Usage` 定义使用命令格式
- `@Options` 定义命令选项参数
- `@Arguments` 定义命令参数
- `@Example` 命令使用例子

## 示例

```php
/**
 * Test command
 *
 * @Command(coroutine=true)
 */
class TestCommand
{
    /**
     * Generate CLI command controller class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Commands</info>)
     * @Options
     *   -y, --yes BOOL             Whether to ask when writing a file. default is: <info>True</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Commands</info>
     *   --suffix STRING            The class name suffix. default is: <info>Command</info>
     *   --tpl-file STRING          The template file name. default is: <info>command.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoCommand class to `@app/Commands`
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
     *   test:{command} [arguments] [options]
     *
     * @Options
     *   -o,--opt   this is command option
     *
     * @Arguments
     *   arg     this is argument
     *
     * @Example
     *   php swoft test:demo arg=stelin -o opt
     *
     * @Mapping()
     */
    public function demo()
    {
        $hasOpt = input()->hasOpt('o');
        $opt    = input()->getOpt('o');
        $name   = input()->getArg('arg', 'swoft');

        App::trace('this is command log');
        Log::info('this is command info log');
        /* @var UserLogic $logic */
        $logic = App::getBean(UserLogic::class);
        $data  = $logic->getUserInfo(['uid1']);
        var_dump($hasOpt, $opt, $name, $data);
    }
}
```

> 命令逻辑里面可以使用 Swoft 所有功能，唯一不一样的是，如果命令不是协程模式运行，所有IO操作，框架底层会自动切换成传统的同步阻塞，但是使用方法是一样的。

## 运行

- 现在你可以执行 `php bin/swoft`, 命令列表中将会显示 test 命令
- 执行 `php bin/swoft test` 或者 `php bin/swoft test -h` 将会看到 test组里拥有的具体命令
- 执行 `php bin/swoft test:test2 -h` 将会看到此命令的完整帮助信息

