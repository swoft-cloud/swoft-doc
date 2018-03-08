# 快速安装

## composer安装
- composer create-project swoft/swoft swoft dev-master

## 手动安装

- clone 项目
- composer install 安装依赖
- 复制根目录下的 .env.example 并粘贴重命名为 .env 并根据需要调整启动参数

## Docker方式安装
docker run -p 80:80 swoft/swoft

## Docker-Compose 安装
- cd swoft
- docker-compose up