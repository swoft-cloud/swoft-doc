# 进程

Swoft 框架中封装了一套经常操作方式，替换 PHP 的 pcntl，PHP自带的pcntl，存在很多不足，如：

- pcntl没有提供进程间通信的功能
- pcntl不支持重定向标准输入和输出
- pcntl只提供了fork这样原始的接口，容易使用错误

> Swoft 基于 Swoole 进程操作封装，功能与 Swoole 完全一样，建议开发者使用 Swoft 的封装操作，方便框架一起迭代升级。2.0.4+ 支持且需要安装 [swoft-process](index.md) 组件


## 方法列表

所有操作在方法，全部在 `Swoft\Process\Process` 里面

- __construct
- start
- name
- exec

...