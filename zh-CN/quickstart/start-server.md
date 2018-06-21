# 服务启动与管理


## 管理

Swoft 拥有完善的命令行工具，和相应的服务器管理命令

## 帮助命令

```text
[root@swoft]# php bin/swoft -h
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|

Usage:
  php bin/swoft {command} [arguments ...] [options ...]

Commands:
  entity  The group command list of database entity
  gen     Generate some common application template classes
  rpc     The group command list of rpc server
  server  The group command list of http-server
  ws      There some commands for manage the webSocket server

Options:
  -v, --version  show version
  -h, --help     show help
```

## HTTP 服务器

> 是否同时启动RPC服务器取决于 `.env` 文件配置

```bash
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft start -d

// 重启
php bin/swoft restart

// 重新加载
php bin/swoft reload

// 关闭服务
php bin/swoft stop
```

## WebSocket服务器

启动WebSocket服务器,可选是否**同时支持http处理**

```bash
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft ws:start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft ws:start -d

// 重启
php bin/swoft ws:restart

// 重新加载
php bin/swoft ws:reload

// 关闭服务
php bin/swoft ws:stop
```

## RPC 服务器

> 使用独立的RPC服务器

```bash
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft rpc:start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft rpc:start -d

// 重启
php bin/swoft rpc:restart

// 重新加载
php bin/swoft rpc:reload

// 关闭服务
php bin/swoft rpc:stop
```
