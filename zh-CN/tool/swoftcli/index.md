# Swoft CLI

Swoft CLI 是一个独立的命令行应用包，提供了一些内置的工具方便开发者使用。

- 将一个swoft应用打包成 phar 包
- 监视用户swoft项目的文件更改并自动重新启动服务器

后续会根据用户需要增加更多的帮助工具，欢迎用户提供意见和贡献代码

> swoft-cli 是基于 swoft 2.0 构建的应用，运行使用同样需要swoole

## 安装

你需要从 swoft-cli 的 [GitHub Releases](https://github.com/swoft-cloud/swoft-cli/releases) 页面下载打好的 `swoftcli.phar` 包

> 注意：需要将下面命令里的 `VERSION` 替换为指定的版本。当然也可以直接通过浏览器下载

```bash
wget https://github.com/swoft-cloud/swoft-cli/releases/download/VERSION/swoftcli.phar

# 检查包是否可用
php swoftcli.phar -V
php swoftcli.phar -h
```

如果你需要在任何地方都可以直接使用 `swoftcli`:

```bash
# move to ENV path:
mv swoftcli.phar /user/local/bin/swoftcli
chmod a+x /user/local/bin/swoftcli

# check
swoftcli -V
```

## 构建

如果你需要从最新的代码构建phar包：

```bash
php -d phar.readonly=0 bin/swoftcli phar:pack -o=swoftcli.phar
```

## 仓库

- github https://github.com/swoft-cloud/swoft-cli

## 参与贡献

您可以 fork 仓库修改然后发起 PR
