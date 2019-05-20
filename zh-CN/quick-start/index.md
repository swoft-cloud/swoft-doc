# 环境搭建

接下来的几篇文章主要介绍swoft需要的运行环境搭建，以及一些使用注意

- 运行swoft需要哪些环境的支持
- 在本机手动运行(只支持Mac Linux)
 - 安装必须的包
- 使用docker运行
 - 使用官方镜像
 - 使用docker-compose

## 环境需求

### 必须安装的

- 安装PHP并且版本至少 `>7.1`
- 安装php包管理器 `composer`
- 连接迭代器依赖 `pcre` 库
- 安装php扩展swoole, 并且版本至少 `>4.3.0`
- 其他需要安装和启用的php扩展有：`PDO` `redis`

### 有冲突的

下面列出一些已知的和swoole有冲突的php扩展，请使用swoft时不要安装或禁用它们：

- `xdebug`
- `xhprof`
- `blackfire`
- `zend`
- `trace`
- `uopz`
