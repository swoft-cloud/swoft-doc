# 安装

安装 swoftcli 非常简单。我们提供已经打包好的phar放在GitHub上。

## 下载

你需要从 swoft-cli 的 [GitHub Releases](https://github.com/swoft-cloud/swoft-cli/releases) 下载打包好的 `swoftcli.phar`

> 注意：需要将下面命令里的 `{VERSION}` 替换为指定的版本。当然也你可以直接通过浏览器下载

```bash
wget https://github.com/swoft-cloud/swoft-cli/releases/download/{VERSION}/swoftcli.phar

# 检查包是否可用
php swoftcli.phar -V
php swoftcli.phar -h
```

## 全局使用

如果你需要在任何地方都可以直接使用 `swoftcli`:

```bash
# move to ENV path:
mv swoftcli.phar /usr/local/bin/swoftcli
chmod a+x /usr/local/bin/swoftcli

# check
swoftcli -V
```

## 构建

如果你需要从最新的代码构建phar包：

```bash
git clone https://github.com/swoft-cloud/swoft-cli
cd swoft-cli 
composer install

// build
php -d phar.readonly=0 bin/swoftcli phar:pack -o=swoftcli.phar
```

