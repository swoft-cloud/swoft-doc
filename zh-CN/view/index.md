# 视图渲染

Swoft 提供了简单方便的视图渲染支持. 使用php原生语法，提供基本都布局，内部引入文件等功能。

## 仓库

github - https://github.com/swoft-cloud/swoft-view

## 安装

视图渲染作为一个额外的独立组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/view dev-master
```

- 通过 composer.json 配置:

```json
    "swoft/view": "dev-master"
```

## 基本信息

视图核心类文件： `\Swoft\View\Base\View`
注解tag类： `Swoft\View\Bean\Annotation\View`
