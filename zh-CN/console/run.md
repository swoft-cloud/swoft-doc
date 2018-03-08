# 运行命令

完成定义命令后，可以执行命令，处理对应业务逻辑

## 查看命令

查看当前已经定义的所有命令，如下操作可以看到定义的test命令组。

```
[root@0dd3950e175b swoft]# php bin/swoft
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php bin/swoft

Commands:
  test    Test command
  entity  the group command list of database entity
  app     There are some help command for application
  server  the group command list of http-server
  rpc     The group command list of rpc server

Options:
  -v,--version  show version
  -h,--help     show help
```

## 查看版本

查看当前 swoft 框架版本信息

```
[root@0dd3950e175b swoft]# php bin/swoft -v

 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

swoft: 0.2.2, php: 7.1.5, swoole: 2.1.0
```

## 查看命令组

查看命令组有哪些操作命令，不加 "-h" 参数也可以。

```
[root@0dd3950e175b swoft]# php bin/swoft test -h
Description:
  Test command

Usage:
  server:{command} [arguments] [options]

Commands:
  test2  this test command
  demo   this demo command

Options:
  -h,--help  Show help of the command group or specified command action
```

## 查看命令帮助信息

查看命令组下面，某个操作命令帮助信息。

```
[root@0dd3950e175b swoft]# php bin/swoft test:test2 -h
Description:
  this test command

Usage:
  test:{command} [arguments] [options]

Arguments:
  arg  this is argument

Options:
  -o,--o  this is command option

Example:
  php swoft test:test arg=stelin -o opt
```

## 执行命令

运行命令组下面，某个操作命令。
```
[root@0dd3950e175b swoft]# php bin/swoft test:test2   
string(4) "test"
object(Input_5a8ecca785da6)#187 (9) {
    ......
}
int(1)
int(15193079439)
```