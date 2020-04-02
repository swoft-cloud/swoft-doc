# 组件结构

## 目录结构

这是官方推荐的组件结构，当然你完全可以自定义。但有一点要注意的是：

> Notice: `AutoLoader.php` 是一个组件必须存在的文件，swoft依据它来确定这是一个组件和要扫描哪些目录

```text
├── src/
│   ├── Annotation/  -------- 组件注解类定义(如果你的组件不需要添加新注解，则无需此目录)
│   │   ├── Mapping/  -------- 注解类定义
│   │   └── Parser/   -------- 注解解析类定义
│   ├── Bean/         ------- 一些具有独立功能的 class bean
│   ├── Concern/      ------- traits/abstract classes
│   ├── Contract/     ------- interface classes
│   ├── Exception/
│   ├── Helper/
│   ├── Listener/      ------- 添加事件监听器
│   ├── AutoLoader.php  -------- 组件扫描等信息
├── test/   ------ 单元测试代码目录
│   ├── unit/
│   ├── testing/
│   └── bootstrap.php
├── LICENSE
├── README.md
├── composer.json
└── phpunit.xml
```

## 快速开始

你可以使用 swoftcli 工具来快速创建一个自定义组件，它会帮你创建好基础的文件结构和入口类：

```bash
php swoftcli.phar create:component my-swoft-component -n 'MySwoft\Component' -o vendor/somedir
```

详细的命令使用请查看 [创建新应用或组件](../tool/swoftcli/create-app-or-component.md)
