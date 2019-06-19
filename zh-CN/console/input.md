# 输入对象

输入对象是 `Swoft\Console\Input\Input` 的实例，用于获取用户输入的命令参数选项等信息。
命令逻辑里面，可以通过函数参数和全局函数获取输入输出对象。

## 获取输入对象

### 通过方法参数

如果需要使用输入和输出对象，可以在操作命令函数上，定义输入和输出对象，底层框架会自动注入对象。

```php
/**
 * Test command
 *
 * @Command(coroutine=true)
 */
class TestCommand
{
    /**
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

### 使用全局函数

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

## 开始使用

通过前一篇 [定义命令](definition.md) 文章，我们已经知道了什么是命令参数，命令选项。

现在，终端中执行如下命令，用于演示参数选项等信息的解析:

```bash
$ php bin/swoft demo:test status=2 name=john arg0 -s=test --page 23 --id=154 -e dev -v vvv -d -rf --debug --test=false
```

> **注意:** 输入如下的字符串将会认为是布尔值

- `on|yes|true` -- `true`
- `off|no|false` -- `false`

## 获取基本信息

```php
echo $input->getScript();  // 'bin/swoft' 执行的入口脚本文件
echo $input->getCommand(); // 'http:start' 命令名称 解析到的第一个参数将会被认为是命令名称，并且不会再存入到 参数列表中
echo $input->getPwd(); // 当前工作目录
```

## 命令参数信息

> 通常的参数如 `arg0` 只能根据 index key 来获取值。但是提供以等号(`=`)连接的方式来指定参数名(eg: `status=2`)

打印所有的参数信息：

```php
var_dump($input->getArgs());
```

output:

```php
array(3) {
  'status' => string(1) "2"
  'name' => string(4) "john"
  [0] => string(4) "arg0"
}
```

### 获取命令参数值

```php
// argument
$first = $input->getFirstArg(); // 'arg0'
$status = $input->getArg('status', 'default value'); // '2'
$status = $input->getInt('status'); // 2
// 获取一个必须的参数，若用户没有输入值，将会抛出错误信息
$id = $input->getRequiredArg('id');
```

## 命令选项信息

获取解析后的选项信息

- 没有值的选项，将设置默认值为 `bool(true)`
- 短选项不仅仅只是以一个 `-` 开头，而且名称 **只能是一个字符**
- 多个(默认值的)短选项可以合并到一起写。如 `-rf` 会被解析为两个短选项 `'r' => bool(true)` `'f' => bool(true)`

打印所有的选项信息：

```php
var_dump($input->getOpts());
// var_dump($input->getLOpts()); // 只打印长选项信息
// var_dump($input->getSOpts()); // 只打印短选项信息
```

output:

```php
array(10) {          
  's' => string(4) "test"   
  'e' => string(3) "dev"    
  'v' => string(3) "vvv"    
  'd' => bool(true)         
  'r' => bool(true)         
  'f' => bool(true)         
  'page' => string(2) "23"     
  'id' =>   string(3) "154"    
  'debug' => bool(true)         
  'test' => bool(false)        
}
```

### 获取选项值

输入对象中提供了非常多的选项值获取方法，方便快速的获取需要的信息。

```php
// option
$page = $input->getOpt('page') // '23'
$page = $input->getIntOpt('page') // 23
$debug = $input->getBoolOpt('debug') // True
$test = $input->getBoolOpt('test') // False

$d = $input->getBoolOpt('d') // True
// 获取到一个值就返回，对同一个含义的选项选项非常有用
$showHelp = $input->sameOpt(['h','help']);
// 获取一个必须的选项，若用户没有输入值，将会抛出错误信息
$id = $input->getRequiredOpt('id');
```

## 读取用户输入

```php
echo "Your name:";

$name = $input->read(); 
echo 'input is ' . $name; // 'inhere'
```

效果(in terminal)：

```text 
$ Your name: inhere
$ input is inhere
```

也可以直接将消息文本放入参数 `$name = $input->read("Your name:");`

