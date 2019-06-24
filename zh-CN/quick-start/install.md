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

## composer安装

```bash
composer create-project swoft/swoft swoft
```
> 通过 `Packagist国内镜像` 加速国内下载速度，请参阅 https://packagist.laravel-china.org

## 手动安装

```bash
git clone https://github.com/swoft-cloud/swoft
cd swoft
composer install --no-dev # 不安装 dev 依赖会更快一些
cp .env.example .env
vim .env # 根据需要调整启动参数
```


