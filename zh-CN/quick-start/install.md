---
parent: 环境搭建
nav_order: 2
---

# 安装Swoft

## Docker方式安装（推荐）

```bash
docker run -p 18306:18306 --name swoft swoft/swoft
```

## Docker-Compose安装（推荐）

```bash
git clone https://github.com/swoft-cloud/swoft
cd swoft
docker-compose up
```

### 提示

- `docker-compose up` 启动后，默认启动了http server，并且它是容器的的第一个进程(`pid=1`)，因此进入容器里是 **没法停止/重启server** 的

如果你想在启动后进入容器，手动管理swoft server的启动。需要先稍微调整下 `docker-compose.yml`:

```yml
  swoft:
    image: swoft/swoft
#    for local develop
    command: php -S 127.0.0.1:13300
```

添加一个commad命令覆盖默认的启动server命令。启动后，进入容器再手动启动server `php bin/swoft http:start`。

此时，server的进程不是容器的第一个进程，就可以使用swoft server的其他命令进行停止/重启操作了。

- 要测试使用db功能时，注意 `host=127.0.0.1:13306` 需改为 `host=mysql:13306`

因为 docker-compose 里关联的db是在另一个容器中，因此不能再使用 `127.0.0.1` 连接

## Composer安装

```bash
composer create-project swoft/swoft swoft
```
> 通过 `Packagist国内镜像` 加速国内下载速度，请参阅 [Composer配置](../ready/composer.md)

## 手动安装

```bash
git clone https://github.com/swoft-cloud/swoft
cd swoft
composer install --no-dev # 不安装 dev 依赖会更快一些
cp .env.example .env
vim .env # 根据需要调整启动参数
```

## 使用Swoftcli

关于swoftcli工具，请查看 [swoftcli 文档](../tool/swoftcli/index.md)。支持快速的从不同模板项目创建一个干净的swoft应用

```php
php swoftcli.phar create:app --type full YOUR_APP_NAME
php swoftcli.phar create:app --type ws YOUR_APP_NAME
php swoftcli.phar create:app --type tcp YOUR_APP_NAME
```

