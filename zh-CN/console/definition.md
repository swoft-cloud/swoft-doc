# 定义命令

一个命令由命令组和执行命令组成，一个类就是一个命令组，类里面的方法对应操作命令，一个命令的运行，是通过执行命令组对应的操作命令。

## 注解

命令的定义主要通过 `@Command` 和 `@CommandMapping`两个注解。
`@Command` 定义命令组名称，`@CommandMapping` 定义操作命令的映射关系。

### Command 注解

**@Command** 定义命令组，标记一个类为console命令类

拥有属性：

- `name` 参数，定义命令组名称，如果缺省，根据类名称自动解析
- `alias` 命令组别名，通过别名仍然可以访问它。_允许多个，以逗号隔开即可_
- `desc` 命令组描述信息说明，支持颜色标签
- `coroutine` 定义是否为协程下运行，默认 true, 框架会启动一个协程运行此组里面的命令

> 若 `desc` 为空，将会自动解析类的第一行注释作为命令组描述

### CommandMapping 注解

**@CommandMapping** 定义操作命令映射关系，标明了一个具体的命令

拥有属性：

- `name` 参数，定义命令组名称，如果缺省，会执行使用方法名称
- `alias` 命令别名，通过别名仍然可以访问它。_允许多个，以逗号隔开即可_
- `desc` 命令的描述信息说明，支持颜色标签

> 若 `desc` 为空，将会自动解析类的第一行注释作为描述

命令帮助信息是命令使用说明信息，也是通过注解完成定义。

- 类描述，对应命令组信息描述
- 方法描述，对应该执行命令的信息描述
- `@Usage` 定义使用命令格式
- `@Options` 定义命令选项参数
- `@Arguments` 定义命令参数
- `@Example` 命令使用例子

## example 注释

`@example` 注释会被特殊处理(**不是注解**)，如果你的命令方法上面有这个注释，它的内容也会被显示到命令帮助信息上面。

## 代码示例

```php
/**
 * Provide some commands for manage and watch swoft server project
 *
 * @Command()
 */
class TestCommand
{
    /**
     * Start the swoft server and monitor the file changes to restart the server
     *
     * @CommandMapping()
     * @CommandArgument("targetPath", type="path",
     *     desc="Your swoft project path, default is current work directory"
     * )
     * @CommandOption("interval", type="integer", default=3,
     *     desc="Interval time for watch files, unit is seconds"
     * )
     * @CommandOption(
     *     "bin-file", short="b", type="string", default="bin/swoft",
     *     desc="Entry file for the swoft project"
     * )
     * @CommandOption(
     *     "start-cmd", short="c", type="string", default="http:start",
     *     desc="the server startup command to be executed"
     * )
     * @CommandOption(
     *     "watch", short="w", default="app,config", type="directories",
     *     desc="List of directories you want to watch, relative the <cyan>targetPath</cyan>"
     * )
     * @example
     *   {binFile} run -c ws:start -b bin/swoft /path/to/php/swoft
     * @param Input $input
     */
    public function run(Input $input): void
    {
        Show::aList([
            'options'   => $input->getOpts(),
            'arguments' => $input->getArgs(),
        ]);
    }
}
```

> 命令逻辑里面可以使用 Swoft 所有功能

## 运行

- 现在你可以执行 `php bin/swoft`, 命令列表中将会显示 test 命令
- 执行 `php bin/swoft test` 或者 `php bin/swoft test -h` 将会看到 test组里拥有的具体命令
- 执行 `php bin/swoft test:run -h` 将会看到此命令的完整帮助信息

