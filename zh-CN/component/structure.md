# 组件结构

## 目录结构

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

