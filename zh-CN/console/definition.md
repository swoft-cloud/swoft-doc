# 定义命令

一个命令由命令组和执行命令组成，一个类就是一个命令组，类里面的方法对应操作命令，一个命令的运行，是通过执行命令组对应的操作命令。

> 命令逻辑里面可以使用 Swoft 所有功能

## 命令结构

```text
                                        value of option: opt1
                                option: opt1  |
                                       |      |
php bin/swoft group:cmd john male 43 --opt1 value1 -y
        |         |      |    |   |                 |
     script    command   |    |   |_____        option: yes, it use shortcat: y, and it is a bool, so no value.
                         |    |___     |
                 argument: name  |   argument: age
                            argument: sex
```

## 注解

命令的定义主要通过 `@Command` 和 `@CommandMapping`两个注解。
`@Command` 定义命令组名称，`@CommandMapping` 定义操作命令的映射关系。

命令的命令使用帮助信息，也是通过注解完成定义。

### Command 注解

**@Command** 定义命令组，标记一个类为console命令类。作用域：`class`

拥有属性：

- `name` _string_ 定义命令组名称，如果缺省，根据类名称自动解析
- `alias` _string_ 命令组别名，通过别名仍然可以访问它。_允许多个，以逗号隔开即可_
- `desc` _string_ 命令组描述信息说明，支持颜色标签
- `coroutine` _bool_ 定义是否为协程下运行，默认 true, 框架会启动一个协程运行此组里面的命令

> Tips: 若 `desc` 为空，将会自动解析类的第一行注释作为命令组描述

### CommandMapping 注解

**@CommandMapping** 定义操作命令映射关系，标明了一个具体的命令。作用域：`method`

拥有属性：

- `name` _string_ 定义命令组名称，如果缺省，会执行使用方法名称
- `alias` _string_ 命令别名，通过别名仍然可以访问它。_允许多个，以逗号隔开即可_
- `desc` _string_ 命令的描述信息说明，支持颜色标签

> Tips: 若 `desc` 为空，将会自动解析类的第一行注释作为描述

### CommandOption 注解

**@CommandOption** 定义一个命令的选项。作用域：`method|class`

拥有属性：

- `name` _string_ **必填项** 定义命令选项名称。eg: `opt`
- `short` _string_ 定义命令选项名称的短选项。
- `default` _mixed_ 命令选项的默认值
- `desc` _string_ 命令选项的描述信息说明，支持颜色标签
- `type` _string_ 命令选项的值类型
- `mode` _int_ 命令选项的值输入限定：必须，可选 等

> Tips: 特别的 `@CommandOption` 可以用 command 类注释上面，这样子相当于给里面所有的命令都加了公共选项。

### CommandArgument 注解

**@CommandArgument** 定义一个命令的参数。作用域：`method`

拥有属性：

- `name` _string_ **必填项** 定义命令参数名称。eg: `opt`
- `default` _mixed_ 命令参数的默认值
- `desc` _string_ 命令参数的描述信息说明，支持颜色标签
- `type` _string_ 命令参数的值类型
- `mode` int 命令参数的值输入限定：必须，可选 等

> Tips: 命令参数是根据输入位置(有顺序的)来获取的，`name` 是代码里给这个位置的参数添加的命名。

## example 注释

`@example` 注释会被特殊处理(**不是注解**)，如果你的命令方法上面有这个注释，它的内容也会被显示到命令帮助信息上面。

## 代码示例

下面是 `sowftcli` 里的 `serve:run` 命令定义。命令帮助显示效果如下：

![hot-restart-help](../image/tool/hot-restart-help.jpg)

```php
/**
 * Provide some commands for manage and watch swoft server project
 *
 * @Command()
 */
class ServeCommand
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

## 使用运行

- 现在你可以执行 `php bin/swoft`, 命令列表中将会显示 serve 组命令
- 执行 `php bin/swoft serve` 或者 `php bin/swoft serve -h` 将会看到 serve组里拥有的具体命令
- 执行 `php bin/swoft serve:run -h` 将会看到此命令的完整帮助信息

