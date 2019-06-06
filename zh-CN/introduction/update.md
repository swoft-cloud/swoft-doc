# 版本更新

## 2.0.2-beta

新增(Enhancement)：
- 新增 `$request->parsedQuery()` 方法 ([ab45089](https://github.com/swoft-cloud/swoft-component/commit/ab450891b126b8d1a76f1eaa5a020fd69b639383))
- 新增 Bean 属性注入基础数据类型(string/int/bool/float/array)，自动根据注释类型转换。([e3d4085](https://github.com/swoft-cloud/swoft-component/pull/424/commits/e3d40856dabd86b85b67a65adae022aeb88f225a))
- Http server 增加cookies响应支持 ([1a024bf](https://github.com/swoft-cloud/swoft-component/pull/426/commits/1a024bf5b9371444bcf59df4e960404bc2c99068))

修复(Fixed)：

- 修复 `paginate` 无法指定查询字段([308d330](https://github.com/swoft-cloud/swoft-component/pull/421/commits/308d330021df702228cab3140ad2d3bb80f5463f))
- 修复 实体 `join` 操作，显示不出关联表数据 ([446a3a2](https://github.com/swoft-cloud/swoft-component/pull/422/commits/446a3a240e3f671a8a635ae08be05a248b528f6d))
- 修复 实体属性名`attributes`等与系统定义属性冲突问题 ([1a9d25b](https://github.com/swoft-cloud/swoft-component/pull/423/commits/1a9d25b0166b4fdff1eb49fd57a28c3dfe598591))
- 修复 AOP 切面重复执行问题 ([54e00ac](https://github.com/swoft-cloud/swoft-component/commit/54e00aca93d8d2d48ce6ec6a710457beaecf8e58))
- 修复 Http 服务全局异常 `contentType` 失效问题([e704116](https://github.com/swoft-cloud/swoft-component/pull/425/commits/e7041164da51af60b9f89bfbd4110d92eb8d9612))
- 修复 Http server在启用https 时，type 检查无法通过 ([3e9b431](https://github.com/swoft-cloud/swoft-component/pull/426/commits/3e9b431f4de5cdbff9d8898062cf3cabf0e76770))
- Console table 数据显示修复 ([4d27718](https://github.com/swoft-cloud/swoft-component/pull/426/commits/4d277189f07903c91415d5045c3d9d3605c8f9f8))
- 修复 devtool 生成的 phpstorm meta文件key重复([1838552](https://github.com/swoft-cloud/swoft-devtool/commit/1838552812b8c3220a3bb2ae161a8ed93250498b))

扩展(Extra):

- `swoft/view` 添加通过 `@View` 渲染视图支持

## 2.0.1-beta (2019-05-29)

新增(Enhancement)：

- 新增验证器不存在错误提示([5ba8682](https://github.com/swoft-cloud/swoft-component/pull/409/commits/5ba8682db1fbd78ddeef62b7f89c02db571cb9a4))
- 新增 AOP 代理类名称标识 `_PROXY_`([5ba8682](https://github.com/swoft-cloud/swoft-component/pull/409/commits/5ba8682db1fbd78ddeef62b7f89c02db571cb9a4))
- 新增实体字段值类型转错误异常提示([ab3cfea](https://github.com/swoft-cloud/swoft-component/pull/411/commits/ab3cfea55955333137f03e364f6ba6feb4ffc1e4))
- 新增 `BeanFactory::getBeans()` 根据类名查询使用实例([999c10f](https://github.com/swoft-cloud/swoft-component/pull/414/commits/999c10f2c14f5678f99c6e1105393b5ce1e3e99e))
- 新增 Model `getAttributeValue` 获取字段值方法([642d791e](https://github.com/swoft-cloud/swoft-component/pull/413/commits/642d791e0b3874f04077ac2db9d9e27b05404a44))
- 新增 Get 方式参数验证([6452a37](https://github.com/swoft-cloud/swoft-component/pull/416/commits/6452a3752e3f8b61ce66b183319b3c26461558e4))
- 新增 `Redis` 通过 `Inject` 方式注入使用([fc0a88e](https://github.com/swoft-cloud/swoft-component/pull/419/commits/fc0a88eb631acddfc19f8d07a83b2d2e1e3e583a))
- 新增 RRC 服务全局异常处理([1653b24](https://github.com/swoft-cloud/swoft-component/pull/420/commits/1653b247ed74a74b1bc1babbeee0e2103176d336))

修复(Fixed)：

- 修复 CLog 日志调用栈错误([868102f](https://github.com/swoft-cloud/swoft-component/pull/409/commits/868102ffa4d8c1f1bae8c7de9ed81caa0e561d6d))
- 修复 Co::multi 调用失败错误数据格式([5ba8682](https://github.com/swoft-cloud/swoft-component/pull/409/commits/5ba8682db1fbd78ddeef62b7f89c02db571cb9a4))
- 修复 Function.php 重复加载([b063dd1](https://github.com/swoft-cloud/swoft-component/pull/410/commits/b063dd1585e529f18cfef52670a45b837d3da791))
- 修复 Logger 日志调用栈错误([ab3cfea](https://github.com/swoft-cloud/swoft-component/pull/411/commits/ab3cfea55955333137f03e364f6ba6feb4ffc1e4))
- 修复 worker 进程退出未释放(close)数据库、缓存、RPC 连接池([5b1baa5](https://github.com/swoft-cloud/swoft-component/pull/414/commits/5b1baa5b92944680689b8cc5fa09c267817fb373))
- 修复 开发者自定义 content-type 失效问题([22cf706](https://github.com/swoft-cloud/swoft-component/commit/22cf706cef9dba5aca2cb7e1185bda545f292ccd))
- 修复 request/session 级别销毁失效问题([e0d1493](https://github.com/swoft-cloud/swoft-component/pull/416/commits/e0d1493407011ed20d49a7d9c959ff4d172b61b1))
- 修复 bean 名称包含 `.` 点号失效问题([dc37c92](https://github.com/swoft-cloud/swoft-component/pull/416/commits/dc37c92319de3dda1221b81e7042a4a4eda72db3))
- 修复连接池 `maxIdleTime` 参数无效问题([0e05591](https://github.com/swoft-cloud/swoft-component/pull/418/commits/0e055913c66744b59c06a461b3f5db2ae87993ba))
- 修复 文件上传 ([10bc7fa](https://github.com/swoft-cloud/swoft-component/pull/412/commits/10bc7fa799a65743e285634ba7c5dd09fec8770c))
- 修复 默认输出编码为UTF8 ([755c8cf](https://github.com/swoft-cloud/swoft-component/pull/412/commits/755c8cfe50bb5971d9f4b0b8af07c02cc5282f8f))
- 优化 Redis 配置类型转换([533b592](https://github.com/swoft-cloud/swoft-component/pull/420/commits/533b5923f1610c1d7c000f6e0bde79036fe41915))
- 打包为phar运行时路径错误([533b592](https://github.com/swoft-cloud/swoft-component/pull/417))

Swoft-cli

- 修复一些问题 [swoft-cli](https://github.com/swoft-cloud/swoft-cli)
- 发布新版本时自动通过travis部署phar到GitHub release ([d1503b2](https://github.com/swoft-cloud/swoft-cli/commit/d1503b209c5e6f663ef3a346d624504f2ab4a94a))

Devtool

- 新 bean 方法代码智能提示(`bean()`/`BeanFactory::getBean()`/`Swoft::getBean()`)([b63fe07](https://github.com/swoft-cloud/swoft-devtool/commit/b63fe07a1ab7140bcc0b78fcde558b8a0cc83172))

## 2.0.0-beta (2019-05-21)

Swoft 2 重构归来，基于 Swoole hook 方式，底层完全重构，相比之前更佳简单、稳定、易上手。

功能特性：

- 内置高性能网络服务器(Http/Websocket)
- 灵活的组件化
- 灵活的注解功能
- 多样化命令终端(Console)
- 强大的切面编程（AOP）
- 完善的容器管理、依赖注入 (DI)
- 灵活的事件机制
- 基于 PSR-7 的 HTTP 消息实现
- 基于 PSR-14 的事件管理器
- 基于 PSR-15 的中间件
- 可扩展的高性能 RPC
- 国际化(i18n)支持
- 简单高效的参数验证器
- 高性能连接池(Mysql/Redis/RPC)，自带断线重连 
- 数据库高度兼容 laravel
- 缓存 Redis 高度兼容 Laravel
- 高效任务处理
- 灵活的异常处理
- 强大的日志系统






