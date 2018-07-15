# 开发准备

## PHPStorm 安装 `PHP Annotations` 插件优化注解使用

通过文本编辑器的环境进行 Swoft 开发，在使用 Swoft 便捷的注解时，仍需要 use 注解相对应的命名空间， 这显然不是一个高效的做法。

我们推荐在 `PHPStorm` 环境下，并安装 Jetbrain 自带的 `PHP Annotations` 插件，可提供注解命名空间自动补全，注解属性代码提醒，注解类跳转等非常有助于提升开发效率的功能。

## 推荐使用 docker 作为开发环境

安装 Docker 环境及加速: [阿里云 - 镜像加速器](https://cr.console.aliyun.com/#/accelerator), 包括 win10/win10以下/linux/mac 环境.

基于 docker 构建环境, 推荐使用 [docker-compose](https://docs.docker.com/compose/), 可以参考项目下的 [docker-compose.yml](https://github.com/swoft-cloud/swoft/blob/master/docker-compose.yml) 文件

```yaml
version: '2'
services:
    swoft-dev:
        image: swoft/swoft:latest
        ports: # 端口映射: 本地端口 -> 容器内端口
            - "80:80"
        volumes: # 文件挂载: 本地文件路径 -> 容器内文件路径
            - /usr/swoft:/var/www
        stdin_open: true
        tty: true
        command: /bin/bash
```

docker 使用可以参考文档 [使用 Docker](quickstart/docker-installation.md)

## 如何在 Mac 搭建本地开发环境

可以参考项目下的 [Dockerfile](https://github.com/swoft-cloud/swoft/blob/master/Dockerfile) 配置环境

- 安装 [homebrew](https://brew.sh/index_zh-tw.html)，并使用 [国内镜像](https://mirrors.tuna.tsinghua.edu.cn/help/homebrew/)，已有请略过

- 安装 PHP 7.1，autoconf，openssl，redis

```bash
brew install php@7.1
brew install autoconf
brew install openssl
brew install redis
```

- 下载 [hiredis](https://github.com/redis/hiredis/releases)，解压后进入相应目录

```bash
make && make install
```

- 下载 [swoole](https://github.com/swoole/swoole-src/releases)，解压后进入相应目录

```bash
phpize && ./configure --enable-async-redis --enable-mysqlnd --enable-coroutine --enable-openssl --with-openssl-dir=/usr/local/opt/openssl
make && make install
sudo echo extension="/usr/local/Cellar/php71/7.1.13_24/lib/php/extensions/no-debug-non-zts-20160303/swoole.so">/usr/local/etc/php/7.1/conf.d/ext-swoole.ini

```

- 使用 `php --ri swoole` 查看信息