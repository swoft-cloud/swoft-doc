# Docker 安装 Swoft

## Docker 环境准备

安装 Docker 环境及加速: [阿里云 - 镜像加速器](https://cr.console.aliyun.com/#/accelerator)

docker 及 docker-compose 基础知识:

- [Docker官方文档](https://docs.docker.com/)
- [composer 入门指南](http://docs.phpcomposer.com/00-intro.html)

## Swoft 源码准备

详见文档 [框架安装](install.md)

## 启动开发环境

[官方 swoft 镜像](https://hub.docker.com/r/swoft/swoft/)

- 基于 [Dockerfile](https://github.com/swoft-cloud/swoft/blob/master/Dockerfile) 的开发环境
- 基于 [docker-compose.yml](https://github.com/swoft-cloud/swoft/blob/master/docker-compose.yml) 的开发环境

## Dockerfile

- 官方镜像已经满足开发需求, 直接使用官方镜像是个不错的选择

```bash
docker run -d -p 80:80 --name swoft swoft/swoft
```

- 如果官方镜像不能满足开发需求, 可以通过修改 [Dockerfile](https://github.com/swoft-cloud/swoft/blob/master/Dockerfile) 来自定义

``` bash
docker build -t swoft/swoft .
docker run -d -p 80:80 --name swoft swoft/swoft
```

- 其他命令

前面已经使用 `--name` 选项将容器命名为 `swoft` 了

``` bash
// 进入容器
docker exec -it swoft bash

// 停止容器
docker stop swoft
```

## docker-compose

```bash
// 运行容器
docker-compose up -d swoft

// 进入容器
docker-compose exec swoft

// 停止容器
docker-compose stop swoft
```

## 运行 Swoft

使用 docker 安装 swoft, 默认已经执行 `php bin/swoft start` 命令来启动 swoft. 访问 http://localhost 验证是否安装成功.

 > 如果采用其他方式部署安装, 执行 php bin/swoft start 命令来启动 swoft

更多 Docker 相关命令请查阅 [Docker官方文档](https://docs.docker.com/)
