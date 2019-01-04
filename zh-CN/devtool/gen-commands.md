# 文件生成命令

使用 `php bin/swoft gen` 可以查看到现在支持生成的文件类型

```bash
% php bin/swoft gen     
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
php bin/swoft gen:controller demo --prefix /demo -y          // Gen DemoController class to `@app/Controllers`
php bin/swoft gen:controller user --prefix /users --rest     // Gen UserController class to `@app/Controllers`(RESTFul type)
```

> 更多选项信息请使用 `php bin/swoft gen:controller -h` 查看

## 生成http middleware

使用命令 `php bin/swoft gen:middleware`

- 使用示例

```bash
php bin/swoft gen:middleware demo    // Gen DemoMiddleware class to `@app/Middlewares`
```

> 更多选项信息请使用 `php bin/swoft gen:middleware -h` 查看

## 生成cli command

使用命令 `php bin/swoft gen:command`

- 使用示例

```bash
php bin/swoft gen:command demo     // Gen DemoCommand class to `@app/Commands`
```

> 更多选项信息请使用 `php bin/swoft gen:command -h` 查看

## 生成ws controller

使用命令 `php bin/swoft gen:websocket`

- 使用示例

```bash
php bin/swoft gen:websocket echo  // Gen EchoController class to `@app/WebSocket`
```

> 更多选项信息请使用 `php bin/swoft gen:websocket -h` 查看

## 生成事件监听器

使用命令 `php bin/swoft gen:listener`

- 使用示例

```bash
php bin/swoft gen:listener demo    // Gen DemoListener class to `@app/Listener`
```

## 生成自定义header头注释

使用命令：`php bin/swoft gen:controller --tpl-dir ./ --tpl-file header`

- 使用示例

```bash
php bin/swoft gen:controller abc --tpl-dir ./templates   // Gen DemoController class to `@app/Controllers`
```

把 /vendor/swoft/devtool/res/templates 目录拷贝出来放到自己想要放置的目录，本示例放在根目录。

修改 file-header.stub 文件，生成代码的使用 --tpl-dir 指定模版目录。

- `--tpl-dir` 注释文件所在目录
- `--tpl-file` 注释文件名称


> 更多选项信息请使用 `php bin/swoft gen:listener -h` 查看


