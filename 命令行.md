# 输入参数解析

> 输入对象是 `inhere\console\io\Input` 的实例  
> 在终端中执行如下命令，用于演示参数选项等信息的解析:
>
> ```bash
> $ php examples/app home/useArg status=2 name=john arg0 -s=test --page=23 --id=154 -e dev -v vvv -d -rf --debug --test=false
> ```
>
> **一点说明：**  
> 没有 `-` 开头的都认为是参数 \(eg: `status=2` `arg0`\)  
> 反之，以 `-` 开头的则是选项数据  
> `--` 开头的是长选项\(long-option\)  
> 一个 `-` 开头的是短选项\(short-option\)  
> 支持混合式选项的赋值 `--id=154` 和 `--id 154` 是等效的  
> **注意:** 输入如下的字符串将会认为是布尔值  
> `on|yes|true` -- `true`  
> `off|no|false` -- `false`
>
> ### 获取命令基本信息:
>
> ```php
> echo $input->getScript();   // 'examples/app' 执行的入口脚本文件
> echo $input->getCommand(); // 'home/useArg' 解析到的第一个参数将会被认为是命令名称，并且不会再存入到 参数列表中
> echo $input->getFullScript(); // 命令行输入的原样字符串
> ```
>
> ### 获取解析后的参数信息
>
> 通常的参数如 `arg0` 只能根据 index key 来获取值。但是提供以等号\(`=`\)连接的方式来指定参数名\(eg: `status=2`\)  
> 打印所有的参数信息：
>
> ```php
> var_dump($input->getArgs());
> ```
>
> output:
>
> ```php
> array(3) {
> 'status' => string(1) "2"
> 'name' => string(4) "john"
> [0] => string(4) "arg0"
> }
> ```
>
> 扩展方法:
>
> ```php
> // argument
> $first = $input->getFirstArg(); // 'arg0'
> $status = $input->get('status', 'default value'); // '2'
> ```
>
> ### 获取解析后的选项信息
>
> 没有值的选项，将设置默认值为 `bool(true)`  
> 短选项不仅仅只是以一个 `-` 开头，而且名称 **只能是一个字符**  
> 多个\(默认值的\)短选项可以合并到一起写。如 `-rf` 会被解析为两个短选项 `'r' => bool(true)` `'f' => bool(true)`  
> 打印所有的选项信息：
>
> ```php
> var_dump($input->getOpts());
> // var_dump($input->getLOpts()); // 只打印长选项信息
> // var_dump($input->getSOpts()); // 只打印短选项信息
> ```
>
> output:
>
> ```php
> array(10) {
> 's' => string(4) "test"
> 'e' => string(3) "dev"
> 'v' => string(3) "vvv"
> 'd' => bool(true)
> 'r' => bool(true)
> 'f' => bool(true)
> 'page' => string(2) "23"
> 'id' =>   string(3) "154"
> 'debug' => bool(true)
> 'test' => bool(false)
> }
> ```
>
> 扩展方法:
>
> ```php
> // option
> $page = $input->getOpt('page') // '23'
> $debug = $input->boolOpt('debug') // True
> $test = $input->boolOpt('test') // False
> $d = $input->boolOpt('d') // True
> $d = $input->sBoolOpt('d') // True
> $showHelp = $input->sameOpt(['h','help']) // 获取到一个值就返回，适合同一个含义的选项
> ```
>
> ### 读取用户输入
>
> ```php
> echo "Your name:";
> $name = $input->read();
> // in terminal
> // Your name: simon
> echo $name; // 'simon'
> ```
>
> 也可以直接将消息文本放入参数 `$name = $input->read("Your name:");`



