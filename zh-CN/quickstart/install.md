# 安装Swoft

## composer安装

安装composer和加速国内速度，请参阅国内镜像 https://packagist.laravel-china.org/

```bash
composer create-project swoft/swoft swoft
```

## 手动安装

```bash
git clone https://github.com/swoft-cloud/swoft
cd swoft
composer install --no-dev # 不安装 dev 依赖会更快一些
cp .env.example .env
vim .env # 根据需要调整启动参数
```

## Docker方式安装

```bash
docker run -p 80:80 swoft/swoft
```

## Docker-Compose 安装

```bash
git clone https://github.com/swoft-cloud/swoft
cd swoft
docker-compose up
```
