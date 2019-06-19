# 自动重启服务

swoft 2.0 在内置组件中去除了自动重启功能，由新的独立工具 swoft-cli 来提供。

> 需要注意的是，2.0 里面是重启整个服务而不是像 1.0 一样只reload工作进程

命令：`serve:run` (也可以直接使用别名 `run`)

## 查看可用选项

```bash
php swoftcli.phar run -h

# 如果已放到了全局PATH里，可以直接这样使用
swoftcli run -h
```

![hot-restart-help](../../image/tool/hot-restart-help.jpg)

### 选项列表

- `-b, --bin-file` 指定swoft应用的入口文件，默认是 `bin/swoft`
- `--interval` 监控文件的间隔时间，默认 3 秒钟检查一次
- `--php-bin` 指定你的php可执行文件，默认会自动从全局path中寻找php
- `-c, --start-cmd` 指定server启动命令，默认是 `http:start` (启动http server)
- `-w, --watch` 指定要监控的目录，相对于应用目录。默认监控 `app,config` 里的文件变动

### 参数列表

`targetPath` 仅有一个参数，指定要运行的swoft应用所在目录，默认为当前目录

## 使用示例

```bash
swoftcli run -c ws:start -b bin/swoft /path/to/php/swoft
```

运行成功后你可以看到如下的信息：

![run-hot-restart](../../image/tool/run-hot-restart.jpg)

当有文件发生变动时，swoft-cli 就会自动的重新启动应用

