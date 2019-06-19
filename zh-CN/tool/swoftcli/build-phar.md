# 打包Phar

swoft-cli 工具提供了一个简单的命令，可用于将一个 swoft应用打包成一个Phar包。

> 要使用此功能，请确保 `phar` 扩展是启用的。通常情况下，这个扩展在php里是默认启用的

## 构建Phar

```bash
php -d phar.readonly=0 swoftcli phar:pack -o=myapp.phar
```

可用选项(_通过帮助查看_)：

- `-c, --config` 指定打包用的配置文件，默认读取当前目录下的 `phar.build.inc`
- `--dir` 要打包的应用目录，默认是当前目录
- `-o, --output` 打包后输出的文件名称，默认是 `app.phar`

> 注意：运行时必须在前面为php加上选项 `-d phar.readonly=0`

运行效果：

![pack-phar](../../image/tool/pack-phar.jpg)

## 展开Phar包

```bash
swoftcli phar:unpack myapp.phar
```


