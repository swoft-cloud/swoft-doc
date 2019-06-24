# 依赖管理工具

Composer 作为依赖包管理工具

> 使用前请确保您已安装PHP 和 [Composer](https://getcomposer.org/download/)

## 使用国内源

- ~laravel-china https://packagist.laravel-china.org~
- huaweicloud https://mirrors.huaweicloud.com/repository/php/

### 方法1：修改全局配置

打开终端并执行如下命令：

```bash
composer config -g repo.packagist composer https://packagist.laravel-china.org
// OR
composer config -g repo.packagist composer https://mirrors.huaweicloud.com/repository/php/
```

### 方法2：修改项目配置

打开终端，进入你的项目的根目录（也就是 `composer.json` 文件所在目录），执行如下命令：

```bash
composer config repo.packagist composer https://packagist.laravel-china.org
// OR
composer config repo.packagist composer https://mirrors.huaweicloud.com/repository/php/
```

您也可以手工在composer.json中添加如下内容：

```json
"repositories": {
    "packagist": {
        "type": "composer",
        "url": "https://mirrors.huaweicloud.com/repository/php/"
    }
}
```

## 相关网址

- PHP官方地址：http://php.net/
- Composer官方地址：https://getcomposer.org/
