# 开发者指南

## 参与 swoft 的开发是否很难

通过对比 swoft 和传统框架, 来回答这个问题:

- 框架定位
    - 传统框架: 适合开发「巨石应用」, 所有功能都包含在同一份代码库中, 适合初创团队快速实现原型
    - swoft: 致力于实现 PHP 下的微服务, 功能都由「组件」提供, 可以按照实际需求按需加载组装
- 开发难度
    - 传统框架
        - 熟悉经典的 lnmp 体系, 只用考虑 PHP 语法
        - 通常需要完整熟悉整个框架, 才可能参与开发, 老旧的框架提 PR 可能很久才会处理
    - swoft
        - 熟悉 swoole 以及网络编程的基础知识, 就能轻松用 PHP 写出并发能力远超 fpm
        - 开发组提供开发指南, 组件说明, 快速响应, 开发者可以选取一个简单的组件上手, 快速完成一个 PR

> 难? 我亦无他, 唯手熟尔.

## 开发步骤

### 使用 docker

推荐使用 docker 解决开发环境的困扰, 开发组维护了[最新的镜像 swoft/alphp](https://github.com/swoft-cloud/alphp), 简单示例:

- 使用 docker-compose 进行服务编排

```yml
version: '3.1'
services: # 定义服务
    swoft: # 服务的名称
        image: swoft/alphp:cli
        volumes:
            - ../:/data # 挂载本地的源码到容器的 /data 目录下
        ports:
            - "80:9501" # 映射本地 80 端口到容器 9501 端口
        links: # 其他基础服务也可以用 docker 解决, 用不到可以先去掉
            - redis
            - mysql
            - rabbitmq       
        tty: true # 开启后, 可以使用 exec 进入到容器中
```

- 简单使用

```bash
docker-compose up -d swoft # 启动服务
docker-compose exec swoft # 进入容器中
```

### swoft 项目说明

swoft 包含2个重要项目:
- [swoft-cloud/swoft-component](https://github.com/swoft-cloud/swoft-component): swoft 源码, 组件都在此项目的 `src/` 目录下 
- [swoft-cloud/swoft](https://github.com/swoft-cloud/swoft): swoft 脚手架, 加载了全部组件并提供了所有组件的使用 demo

换一个说法:
- 参与 `swoft`, 其实在做应用层的事, 业务中要怎么写, 参考 swoft 中的 demo 就行, 通俗说 -- `造飞机`
- 参与 `swoft-component`, 其实是参与其中具体的某个/某几个组件, 通俗说 -- `造飞机引擎`

swoft 的 **架构设计**, 体现在 swoft 的 **组件化** 实现中:

- 框架组件 framework: 框架核心工作, 以及框架核心配套的组件
- swoft 功能组件: 按照功能进行划分, 依赖框架组件, 可以按需加载, 常见的有
    - rpc 全家桶: rpc rpc-server rpc-client
    - cli 引用: console

> 完全组件参考: [组件说明与功能列表](component.md)

如果只是想 `造飞机`, 只用给 `swoft` 项目提 PR 即可; 如果想 `造飞机引擎`, 需要给 `swoft-component` 提 PR, 本地还需要一份类似 `swoft` 项目的脚手架, 用来验证

### 举个例子: 参与 RPC 组件开发

如果对 RPC 的基本概念不熟悉, 参考: [RPC 组件](rpc.md)

新建一个空文件夹, 比如 swoft-test, 充当 `swoft` 项目的角色, 作为脚手架, 加载需要的组件

- 需要的基础文件

```
➜  swoft-test tree -L 2
.
├── app
│   ├── Application.php
│   └── bean.php
├── bin
│   ├── bootstrap.php
│   └── test.php
├── composer.json
├── config
```

是的, 只需要这么多, 从 swoft 复制过来即可

- 使用 composer 加载 swoft 组件

```json
{
  "require": {
    "swoft/rpc": "dev-master",
    "swoft/rpc-server": "dev-master",
    "swoft/rpc-client": "dev-master"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    },
  },
  "minimum-stability": "dev",
  "repositories": {
    "hyperf": {
      "type": "path",
      "url": "../swoft-component/src/*"
    },
    "packagist": {
      "type": "composer",
      "url": "https://packagist.laravel-china.org"
    }
  }
}⏎
```

这里涉及 composer 从本地加载依赖文件的用法, 不熟悉可以参考 [官方文档 composer-repository](https://getcomposer.org/doc/04-schema.md#repositories)

好了, 更新:

```
composer u --no-dev
```

之后就可以执行 `php bin/swoft` 来进行相应测试了, 如果遇到报错, 根据报错添加缺少的组件即可

- 修改 RPC 相关文件

修改 `swoft-component` 下的组件, 建议多和开发组交流, 有开发范式和 CI 来保证组件的开发质量和稳定性

## 需要了解的更多知识

- docker 基础知识
- github 如何提 PR. 推荐一个好用的工具, github desktop, 有快捷键快速提 PR.
- composer 文档, 组件开发和生产项目有所差异, composer 提供了一些有意思的功能, 方便开发组件

推荐使用 mac + phpstorm + docker, 构建全套开发环境. 有相关问题, 欢迎和开发组交流.