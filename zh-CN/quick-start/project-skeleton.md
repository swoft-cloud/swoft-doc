# 应用结构

一个完整的swoft应用可以包含：

- console 应用
- http 服务(跟传统的框架差不多)
- websocket 服务
- rpc 服务

> `swoft-cloud/swoft` 即是一个完整应用的demo。当然，如果你只想使用一部分功能也是可以的

## 应用骨架

> `app` 下的类目录为了避免一些文件夹名称没有复数单词，导致命名不统一，所有的文件夹名称 **统一使用单数**

```text
├── app/   ------ 应用代码目录
│   ├── Annotation/   ------- 定义注解相关
│   ├── Aspect/       ------- AOP 切面
│   ├── Bean/         ------- 一些具有独立功能的class bean
│   ├── Console/      ------ 命令行代码目录
│   │   ├── Command/
│   ├── Exception/      ------ 定义异常类目录
│   │   └── Handler/     ------ 定义异常处理类目录
│   ├── Http/         ------ HTTP 代码目录
│   │   ├── Controller/
│   │   └── Middleware/
│   ├── Helper/
│   │   └── Functions.php
│   ├── Listener/     ------ 事件监听器目录
│   ├── Model/        ------ 模型、逻辑等代码目录(这些层并不限定，根据需要使用)
│   │   ├── Dao/
│   │   ├── Data/
│   │   ├── Logic/
│   │   └── Entity/
│   ├── Rpc/          ------ RPC 代码目录
│   │   └── Service/
│   │   └── Middleware/
│   ├── WebSocket/     ------ WebSocket 代码目录
│   │   ├── Chat/
│   │   ├── Middleware/
│   │   └── ChatModule.php
│   ├── Application.php -------- 应用类文件继承自swoft核心
│   ├── AutoLoader.php  -------- 项目扫描等信息(应用本身也算是一个组件)
│   └── bean.php
├── bin/
│   ├── bootstrap.php
│   └── swoft   ------ swoft 入口文件
├── config/     ------ 应用配置目录
│   ├── base.php  --- 基础配置
│   └── db.php
├── public/     ------ WEB可访问目录
├── resource/   ------ 应用相关资源目录
│   ├── language/   ------ 语言资源目录  
│   └── view/       ------ 视图资源目录  
├── runtime/    ------ 临时文件目录(日志、上传文件、文件缓存等)
├── test/       ------ 单元测试代码目录
│   └── bootstrap.php
├── composer.json
├── composer.lock
├── phar.build.inc
└── phpunit.xml.dist
```

> render by `tree -L 2 -F --dirsfirst`


## 组件骨架结构

```
├── src/
│   ├── Annotation/  -------- 组件注解类定义
│   ├── Bean/         ------- 一些具有独立功能的 class bean
│   ├── Concern/      ------- traits classes
│   ├── Contract/     ------- interface classes
│   ├── Exception/
│   ├── Helper/
│   ├── Listener/
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

> `src/AutoLoader.php` 是一个组件必须存在的文件，swoft依据它来确定要扫描那些目录

### 开发自定义组件

在2.0版本你可以非常容易的开发一个swoft组件。延伸阅读 [开发自定义组件](../component/index.md)
