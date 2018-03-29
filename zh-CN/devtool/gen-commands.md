# 文件生成命令

使用 `php bin/swoft gen` 可以查看到现在支持生成的文件类型

```bash
% php bin/swoft gen                                                                                                                                                          18-03-29 - 10:22:23
Description:
  Generate some common application template classes[built-in]

Usage:
  gen:{command} [arguments] [options]

Commands:
  command     Generate CLI command controller class
  controller  Generate HTTP controller class
  websocket   Generate WebSocket controller class
  rpcService  Generate RPC service class
  listener    Generate an event listener class
  middleware  Generate HTTP middleware class
  task        Generate user task class
  process     Generate user custom process class

Options:
  -h, --help  Show help of the command group or specified command action

```

> 关于每个命令的具体使用信息，可以用 `php bin/swoft gen:{command} -h` 来查看 

## 生成http controller

使用命令 `php bin/swoft gen:controller`

- 使用示例

```bash
php bin/swoft gen:controller demo --prefix /demo -y          Gen DemoController class to `@app/Controllers`
php bin/swoft gen:controller user --prefix /users --rest     Gen UserController class to `@app/Controllers`(RESTFul type)
```

## 生成cli command

使用命令 `php bin/swoft gen:command`

- 使用示例
