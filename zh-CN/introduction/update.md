# 版本更新

## v2.0.7[WIP]

**修复(Fixed)**：

- 修复 config 注入时，没有找到值也会使用对应类型的默认值覆盖属性，导致属性默认值被覆盖 [d84d50a7](https://github.com/swoft-cloud/swoft-component/pull/522/commits/d84d50a76c4c7ff19dc0896868745cfe8f0d93c9)
- 修复 ws server 中使用message调度时，没有过滤空数据，导致多发出一个响应。避免方法[swoft-cloud/swoft#1002](https://github.com/swoft-cloud/swoft/issues/1002) [d84d50a7](https://github.com/swoft-cloud/swoft-component/pull/522/commits/d84d50a76c4c7ff19dc0896868745cfe8f0d93c9)
- 修复 tcp server 中使用message调度时，没有过滤空数据，导致多发出一个响应。[07a01ba1](https://github.com/swoft-cloud/swoft-component/pull/522/commits/07a01ba1e6ff52baffbc7b2baf997e0e6a07ae04)
- 修复 独立使用console组件时缺少 swoft/stdlib 库依赖 [c569c81a](https://github.com/swoft-cloud/swoft-component/pull/529/commits/c569c81ae15c0b2b73db3a15c457d7b982a06d7f)
- 修复 `ArrayHelper::get` 传入key为 integer 时，报参数错误 [a44dcad](https://github.com/swoft-cloud/swoft-component/pull/528/commits/a44dcad42cbdd20cb4078351a8dea3b966b1ca09)

**更新(Update)**:

- 更新 console 在渲染 help信息之前也会发出事件 `ConsoleEvent::SHOW_HELP_BEFORE` [d3f7bc3](https://github.com/swoft-cloud/swoft-component/pull/522/commits/d3f7bc3c5093a11a1de3710fd239c4375b835160)
- 简化和统一 http,ws,tcp,rpc server管理命令逻辑 [f202c826](https://github.com/swoft-cloud/swoft-component/pull/522/commits/f202c826b74972775fe97ad91b2c38e5c7d97014)
- 更新 ws 和 tcp Connection类添加 `newFromArray` 和 `toArray` 方法，方便通过第三方存储(`redis`)时导出信息和恢复连接。

**增强(Enhancement)**：

- 现在 tcp 请求支持添加全局或对应的方法中间件，流程和使用跟http中间件类似 [6b593877](https://github.com/swoft-cloud/swoft-component/pull/528/commits/6b593877acc5cb78bbd863e08c0559454fb0b59c)

## v2.0.6(2019-09-21)

> 升级提示：
- 通过参数注入接收websocket原始数据时，现在需要加上类型 `string`。例如： `public function echo(string $data)`

**修复(Fixed)**：

- 修复 ws server收到消息时，默认使用当前fd作为响应消息的客户端 [3a8890cf](https://github.com/swoft-cloud/swoft-component/pull/504/commits/3a8890cfd5a4641042eb7714c6afe65e15b0b461)
- 修复 ws server通过swoole server拿到的全部连接里，fd的类型可能不是整型导致报错。[0fa79d7](https://github.com/swoft-cloud/swoft-component/pull/504/commits/0fa79d7e5747d47883a104e2d6ab24c90b745453)
- 修复 stdlib SystemHelper 在 windows 10 中会被 Defender 删除 [71c2893](https://github.com/swoft-cloud/swoft-component/pull/505/commits/71c2893c8514e5535b77a3b8bb8b2c225c771c8a)
- 修复 `increment/decrement` 多拿了一个连接 [94c7171](https://github.com/swoft-cloud/swoft-component/pull/502/commits/94c7171f57c4d8952fdb0214da27e6e12231fe2d)
- 修复 模型属性 `$modelTimestamps` 关闭了无效 [94c7171](https://github.com/swoft-cloud/swoft-component/pull/502/commits/94c7171f57c4d8952fdb0214da27e6e12231fe2d)
- 修复 在初始化前调用了log，导致可能会在启动阶段报context丢失错误 [5a27704f](https://github.com/swoft-cloud/swoft-component/pull/504/commits/5a27704f1bd3a056b144e2ecbb4accf1c59680df)
- 修复 ws server的握手和open使用同一个顶级协程ID，如果有一个先执行完成，可能会导致后面的出现session丢失 [c2cd17a](https://github.com/swoft-cloud/swoft-component/pull/509/commits/c2cd17aaf0989ef9f4a2feaac714e152c1795d8c)
- 修复 `after` 定时器传递参数 bug [8ed609a](https://github.com/swoft-cloud/swoft-component/pull/508/files)
- 修复定时任务内存泄漏和日志打印数据丢失问题 [aa6bc32](https://github.com/swoft-cloud/swoft-ext/pull/40/commits/aa6bc3223a4fc7b3cb97ec1f324c257eaa2356bf)
- 优化 Http server `sgo` 处理流程 [e60d92e](https://github.com/swoft-cloud/swoft-component/pull/507/commits/e60d92e78853a9f0b2e9f823c941bc214c6c409f)
- 修复数据库在事物情况下重连导致数据不一致问题 [c2e87f2](https://github.com/swoft-cloud/swoft-component/pull/507/commits/c2e87f278815fd14dbccb71d5f6a63d4f974d38b)
- 修复 Aop 极端情况返回 `0` 问题 [85bc1b1](https://github.com/swoft-cloud/swoft-component/pull/511/commits/85bc1b186f125773d55c582ecc40cde9f30c87cf)
- 修复 `BeanFactory::createBean()` 方法创建问题[c2e2c89](https://github.com/swoft-cloud/swoft-component/pull/474/commits/c2e2c894ea17dcb3a0e97cccaff42355b72066f7)
- 修复 RPC 错误数据是字符串问题 [3a34658](https://github.com/swoft-cloud/swoft-component/pull/474/commits/3a346589a81e35a0dd1b2ffc43dcd430153d86c7)
- 修复 `redisCluster` 初始化问题[3a60c06](https://github.com/swoft-cloud/swoft-component/pull/515/commits/3a60c063189224a360400f9fcfd9d5cb55bd3587)
- 修复 `DbException` 没有抛出 `Database` 的异常 `Code`[3a60c06](https://github.com/swoft-cloud/swoft-component/pull/515/commits/3a60c063189224a360400f9fcfd9d5cb55bd3587)
- 修复 `ModelAttribute` 的 `getter` 和 `setter` 驼峰判断错误[2e2f0f1](https://github.com/swoft-cloud/swoft-component/pull/502/commits/2e2f0f197c64fecfdee82ade45a40ed509ed6108)
- 修复 `Aggregate` 聚合, 返回字符串强行转换 `int`错误[7aa98a6](https://github.com/swoft-cloud/swoft-component/pull/502/commits/7aa98a6a565236639c9803facf2a4b01bc2adacb)
- 修复模型 `updateCounters` 方法更新之后没有同步实体数据[967d6f8](https://github.com/swoft-cloud/swoft-component/pull/502/commits/967d6f84e3e1eae4c495e46c5a5edbfa79b5a7fe)

**更新(Update)**:

- 调整 ws server在worker 退出时自动关闭连接，添加开关配置，可以选择是否开启。[3a8890c](https://github.com/swoft-cloud/swoft-component/pull/504/commits/3a8890cfd5a4641042eb7714c6afe65e15b0b461)
- ws server 新增事件： `MESSAGE_PUSH` `OPEN_BEFORE` `MESSAGE_RECEIVE` [e1b0583](https://github.com/swoft-cloud/swoft-component/pull/504/commits/e1b058340e27e6773a3fbac0daafaffbabea5571)

**增强(Enhancement)**：

- 新增日志按时间切割 [f649df2](https://github.com/swoft-cloud/swoft-component/pull/503/commits/f649df2cf6bb7635ae2f0bff8ae8250e4bdecbf3)
- 新增日志支持传递额外参数 [55d5533](https://github.com/swoft-cloud/swoft-component/pull/503/commits/55d55335f77572611ae0a469f5c848454838fc1f)
- 调整和新增了一些ws server相关事件，方便用户使用 [e1b0583](https://github.com/swoft-cloud/swoft-component/pull/504/commits/e1b058340e27e6773a3fbac0daafaffbabea5571)
- 模型对 `Prop` 支持 [630aad8](https://github.com/swoft-cloud/swoft-component/pull/502/commits/630aad8315894a993ef9021c23c70eed14e3fa47)
- 新增连接连接池(mysql/redis/rpc)启动初始化连接功能 [a367173](https://github.com/swoft-cloud/swoft-component/pull/507/commits/a3671739863bacf1ba514fbe637e981b19b22b94)
- tcp server 使用长度分包时，支持自定义头部数据打包、解包格式 [a6fbe932](https://github.com/swoft-cloud/swoft-component/pull/509/commits/a6fbe93261b2facadd0c9d3384c3abd86e8779dc)
- RPC 调用错误信息调整 [d9226b1](https://github.com/swoft-cloud/swoft-component/pull/515/commits/d9226b13e7bf1b3154922439dc4990c8f55de040)
- 新增操作 `Database` 异常, 打印控制台 错误 `SQL`[3a60c06](https://github.com/swoft-cloud/swoft-component/pull/515/commits/3a60c063189224a360400f9fcfd9d5cb55bd3587)
- 新增操作数据迁移 `int` 系列 长度支持[967d6f8](https://github.com/swoft-cloud/swoft-component/pull/502/commits/967d6f84e3e1eae4c495e46c5a5edbfa79b5a7fe)
- 新增获取原始 sql 方法`getRawSql`[967d6f8](https://github.com/swoft-cloud/swoft-component/pull/502/commits/967d6f84e3e1eae4c495e46c5a5edbfa79b5a7fe)
- 扩展`updateOrInsert`和`updateOrCreate` 支持自增参数[967d6f8](https://github.com/swoft-cloud/swoft-component/pull/502/commits/967d6f84e3e1eae4c495e46c5a5edbfa79b5a7fe)


**扩展(Extra)**:

- `swoftcli` 支持类生成，创建新的应用、组件结构。使用请查看文档
- `swoftTracker` 支持方便开发者调试

## v2.0.5-beta(2019-08-06)

> 升级提示：
- 请使用 `context()` 代替 `Context::mustGet()`
- 这个版本开始验证器默认关闭，需要开发者开启 [validator setting](https://www.swoft.org/docs/2.x/zh-CN/validator/setting.html")

修复(Fixed)：

- 修复提前中断请求逻辑可能导致无法正确的格式化对应的Content-Type [f031398](https://github.com/swoft-cloud/swoft-component/pull/493/commits/f03139886a471a0424d236061e8cb30d90b32a89)
- 修复使用sgo创建子协程，在结束时没有清理与顶级协程的映射关系 [de11ae5b](https://github.com/swoft-cloud/swoft-component/pull/493/commits/de11ae5bc63833b80ca491132d156d51b95f6c8d)
- 修复 Xml 格式解析方法 [24d0038](https://github.com/swoft-cloud/swoft-component/pull/495/commits/24d0038dc644fccf238d9642f049ceb961dd22b3)
- 修复类代理 Unique 字符串问题 [444ddeb](https://github.com/swoft-cloud/swoft-component/pull/495/commits/444ddeb51e0741dda06e8f548f2579cf534bdf30)
- 修复 `context()->get()` 存在问题以及废弃 `Context::mustGet()` 方法 [6acc1b5](https://github.com/swoft-cloud/swoft-component/pull/496/commits/6acc1b55163a18edc95de6cdb899398a9a7d0f2e)
- 修复接口注入，类名访问错误 [2d9a31f](https://github.com/swoft-cloud/swoft-component/pull/496/commits/2d9a31fe1855054055daa6e6e73aeaddfa280900)
- 修复 模型使用 `paginate` 方法, 结果集没有映射字段[3027287](https://github.com/swoft-cloud/swoft-component/commit/3027287a58bfa66b3c87b8d2d03fddbfc6c2c754)
- 修复 websocket server 里，当有多个worker，主动关闭非当前worker连接时报错 [7666969](https://github.com/swoft-cloud/swoft-component/pull/500/commits/7666969f731dce68d395f45749260731eead7bda)

更新(Update):

- 默认的 worker num 设置通过 `swoole_cpu_num` 函数获取 [553f6500](https://github.com/swoft-cloud/swoft-component/pull/493/commits/553f6500678b258080b84cffeab0fe2d5bf65550)
- 验证器调整成，默认非必须，用户自己开启 [e5b258d](https://github.com/swoft-cloud/swoft-component/pull/496/commits/e5b258db35683e50f4abeba22a2c6fa82dbc7ceb)
- 不再抛出swoft内部的 `ContainerException` 到用户使用层 [1e74893](https://github.com/swoft-cloud/swoft-component/pull/498/commits/1e74893bf6e05fe954334e527e8b76eca2cef6e9)
- console output 新增 `confirm` `select` 等交互方法 [0181138a](https://github.com/swoft-cloud/swoft-component/pull/498/commits/0181138af6f1bc1dc0f3f1025b23d9a6354d122a)

增强(Enhancement)：

- console 命令选项设置 `CommandOption`，现在允许选项描述为多行信息 [e5914983](https://github.com/swoft-cloud/swoft-component/pull/493/commits/e591498363dac1888f503ab18766e89542e33665)
- websocket server 的全部消息发送方法，支持传入 `opcode` 参数 [dc164ffe9](https://github.com/swoft-cloud/swoft-component/pull/493/commits/dc164ffe97d507e505ac8cd1b50a421a27ae5859)
- websocket 模块允许设置当前模块的默认 `opcode`，用于自动处理返回数据设置 `opcode` [9e3e9672](https://github.com/swoft-cloud/swoft-component/pull/493/commits/9e3e9672ed83579e6135ed47ae0c30ef335d8659)
- 增强 websocket 相关类，现在消息处理方法允许注入更多数据对象类型 `Request` `Response` [3d6c60b4](https://github.com/swoft-cloud/swoft-component/pull/493/commits/3d6c60b4812535c48d51c57476f801529f66655f)
- 当worker退出时，websocket server 会自动关闭所有连接 [5ea10ecc](https://github.com/swoft-cloud/swoft-component/pull/498/commits/5ea10ecc4bcf767b374dad9652dd57a4fcfc8fc5)
- Add Timer 定时器封装 [eb92ee6](https://github.com/swoft-cloud/swoft-component/pull/495/commits/eb92ee6a7d5e7f2a47e30920a83ed192626b42e3)
- Add Coroutine 方法封装 [d8c2b88](https://github.com/swoft-cloud/swoft-component/pull/495/commits/d8c2b8848d492fc4b389df6edee595ef8f0e39c5)

扩展(Extra):

- [swoft/crontab](https://github.com/swoft-cloud/swoft-crontab.git) 秒级别定时任务
- [swoft/whoops](https://github.com/swoft-cloud/swoft-whoops.git) 对 `filp/whoops` 在swoft中使用的简单封装，用于渲染并显示更加利于阅读的错误信息


## v2.0.4-beta(2019-07-21)

> 升级注意：
1. 请去掉 `bin/swoft` 里的 `Runtime::enanbleCoroutine()` 设置
2. 请确保 swoole 的 `swoole.use_shortname` 的值为 `On` 
3. 从这个版本开始要求 swoole `4.4.1+`

增强(Enhancement)：

- `Swoft\Http\Message\Request` 新增 `getHeaderLines()` ([74a2a91](https://github.com/swoft-cloud/swoft-component/pull/480/commits/74a2a91d62dd4230c8cffca621c95354a251ac82))
- Aop 新增 `getArgsMap()` 和 `getClassName()` 方法 ([c47e785](https://github.com/swoft-cloud/swoft-component/pull/480/commits/c47e785ef40bc65a98fabc81c73ee0099228c3aa))
- 新增 `srun()` 函数，用于协程调度 ([3c4a6a4](https://github.com/swoft-cloud/swoft-component/pull/475/commits/3c4a6a41976c4813f8c60b2f5321d0a25d3572dc))
- 优化 server 事件(`onStart` / `onWorkStart` / `onWorkStop` / `onShutdown`)，事件自带支持协程 ([a8d5a8d](https://github.com/swoft-cloud/swoft-component/pull/475/commits/a8d5a8da82f236d485ac771e8668efa62d8033b9))
- 新增投递同步阻塞任务([ec938e5](https://github.com/swoft-cloud/swoft-component/pull/480/commits/ec938e556a32849dc62fe7f8eaaab4b13c853279))
- 新增 Redis `call` 方法, 用于使用同一连接操作([92456987](https://github.com/swoft-cloud/swoft-component/pull/482/commits/924569877aa3305dbd4ad573e537907a6ed43404))
- 兼容 Swoole 4.4.x

修复(Fixed)：

- 修复 迁移类名太长导致记录类名不全([58314b8](https://github.com/swoft-cloud/swoft-ext/pull/9/commits/58314b87d38b6579eff9123d58713276bc22c913))
- 修复 实体查询之后使用`Setter`更新字段值之后`update`更新无效([caadf0e](https://github.com/swoft-cloud/swoft-component/pull/472/commits/caadf0e9e24e470465656cf46f1199f735ae6358))
- 修复 stop 后删除pid文件的结果返回错误，导致restart失败 ([2be450bf11](https://github.com/swoft-cloud/swoft-component/pull/470/commits/2be450bf11db5cab7dc5ac5facfab336a9a59acf))
- 修复 i18n 设置默认语言不生效的问题 ([b401a504e](https://github.com/swoft-cloud/swoft-component/pull/478/commits/b401a504e43607be119ae6e97e0633133aabb24c))
- 修复 ws server在有多个worker时，无法主动关闭其他worker的连接([271b6398](https://github.com/swoft-cloud/swoft-component/pull/478/commits/271b63988065882da394e1e860b9142061d92aeb))
- 修复 http server接收xml请求时，content type 不能正确匹配([2ff9a4e61](https://github.com/swoft-cloud/swoft-component/pull/478/commits/2ff9a4e6181a9e96bbfa2454abab62d886e705b9))
- 修复 使用 Database, `json` 操作无效([92456987](https://github.com/swoft-cloud/swoft-component/pull/482/commits/924569877aa3305dbd4ad573e537907a6ed43404))
- 修复 limiter 限速器 Redis 加前缀无法使用问题([7b54d4c](https://github.com/swoft-cloud/swoft-ext/pull/15/commits/7b54d4c3b680299e096950206443aee94d9d859b))

更新(Update):

- 更新 ws server 可以通过配置 `disabledModules` 来禁用 ws 模块([fa31111d](https://github.com/swoft-cloud/swoft-component/pull/478/commits/fa31111d2f5ab9dbf4d858f1226ad4dade407c32))

扩展(Extra):

- 在官网增加案例展示，欢迎大家提交案例到官方案例仓库 [swoft-cloud/swoft-case](https://github.com/swoft-cloud/swoft-case)
- 在GitHub上对文档的修改，将会自动更新到官网文档，不再需要手动刷新

新增(New)

- 进程管理([c61b8f7](https://github.com/swoft-cloud/swoft-component/pull/480/commits/c61b8f77a259aef1e7b5bded86cd0c1e0ca5d1fd))
- 自定义用户进程([c328d54](https://github.com/swoft-cloud/swoft-component/pull/480/commits/c328d540236a710b6380c9470fd1b651d2d67dfe))
- 进程池([d2e6290](https://github.com/swoft-cloud/swoft-component/pull/480/commits/d2e6290f5a397d038c0aabe03fc719493207562f))
- TCP server 基本可用

## v2.0.3-beta(2019-07-08)

不兼容(Incompatible)

- 移除 `request->json()` 方法([c9e8f04](https://github.com/swoft-cloud/swoft-component/pull/455/commits/c9e8f048217670fd975e70d4eee1d8d3b2fccd7e)) 替代方法 `getParserdBody()`
- 应用日志和控制台日志 `levels` 由数组配置改成，字符串配置，详细见文档

新增(Enhancement)：

- 新增接口依赖注入([6169f84](https://github.com/swoft-cloud/swoft-component/pull/442/commits/6169f84ffd32622c4d3bb21f6ab3c69ef04746e2))
- 新增 `getFile` 方法获取文件上传保存之后的信息([fe7e3a6](https://github.com/swoft-cloud/swoft-component/commit/fe7e3a69f215a92af83c5837d32b72a5c181383a))
- 新增 `restart()` 服务新增重启方法([2ffec37](https://github.com/swoft-cloud/swoft-component/pull/453/commits/2ffec37568618cc49c038f96a901cbb339769623))
- 新增调用 1.x RPC 服务支持([30d73c3](https://github.com/swoft-cloud/swoft-component/pull/455/commits/30d73c3bcef6294d5985ce5af316e7aa6e86c0e5))
- 新增 AOP 类名匹配支持正则表达式([bc5e479](https://github.com/swoft-cloud/swoft-component/pull/455/commits/bc5e47951d1426e6944b934e6c8eb5db5657f8a4))
- 新增 RPC Server /Http Server 中间件命名空间 `use` 错误提示([b1cec04](https://github.com/swoft-cloud/swoft-component/pull/455/commits/b1cec041ea402fe1c837d6755eff6efbfcef3681))
- 新增 验证器排除属性字段 `unfields`([b1bf44f](https://github.com/swoft-cloud/swoft-component/pull/459/commits/b1bf44f62ae096674f2c413e9630964782b14d3f))
- 新增 自动写入时间戳([dc58011](https://github.com/swoft-cloud/swoft-component/pull/457/commits/dc58011cfa90996e6e319365fc738c1cd386f08d))
- 新增 模型动作事件([dc58011](https://github.com/swoft-cloud/swoft-component/pull/457/commits/dc58011cfa90996e6e319365fc738c1cd386f08d))
- 新增 数据库迁移([26bb464](https://github.com/swoft-cloud/swoft-ext/commit/26bb464ba61e43b6bbae9ba191a8e69525ad7dc5))
- 新增 实体自动与 json 和数组互转([dc58011](https://github.com/swoft-cloud/swoft-component/pull/457/commits/dc58011cfa90996e6e319365fc738c1cd386f08d))
- 新增 模型批量更新方法`batchUpdateByIds`([dc58011](https://github.com/swoft-cloud/swoft-component/pull/457/commits/dc58011cfa90996e6e319365fc738c1cd386f08d))


修复(Fixed)：

- 修复 cookies 设置时的一些问题，增加一些 withCookie 相关方法([b05afbb01](https://github.com/swoft-cloud/swoft-component/pull/443/commits/b05afbb011457d217bb3a40c6a3639c28b0eef18))
- 修复 在console使用协程方式运行命令时，没有捕获处理错误([8a5418bf](https://github.com/swoft-cloud/swoft-component/pull/443/commits/8a5418bf25a4d4797d99281bf7db8881b7ca43ed))
- 修复 websocket server 重启命令没有先停止旧server问题([db2d935](https://github.com/swoft-cloud/swoft-component/pull/443/commits/db2d935542c715bbe1ed4b3ed822b2d946317b6f))
- 修复任务返回值为 `null` 问题([a69347c](https://github.com/swoft-cloud/swoft-component/pull/455/commits/a69347c8ce486102e23bb82d70cfef63573a94eb)) 
- 修复 RPC Server 只有类中间件无法使用问题()[204bc7f](https://github.com/swoft-cloud/swoft-component/pull/455/commits/204bc7f4f23a26f75534ac808e2f9eb05a0118a2)
- 修复 RPC Server 返回值为 `null` 问题([4d091be](https://github.com/swoft-cloud/swoft-component/pull/455/commits/4d091bef0ece773bcd382a750de01eef830f2949))
- 修复 Logger 和 CLog 日志等级无法覆盖和无效问题([8eb8aba](https://github.com/swoft-cloud/swoft-component/pull/459/commits/8eb8aba2313a45191318b52a3698135d92cb3e71))
- 修复 模型里面的属性不支持自定义表达式([dc58011](https://github.com/swoft-cloud/swoft-component/pull/457/commits/dc58011cfa90996e6e319365fc738c1cd386f08d))

更新(Update):

- 验证器优化，支持自定义验证规则([d959a4f](https://github.com/swoft-cloud/swoft-component/pull/442/commits/d959a4f5facfc24070fbde547f0557213e5184ee))
- 重命名错误处理管理类 `ErrorHanlders` 为 `ErrorManager` ([f3a8f04b](https://github.com/swoft-cloud/swoft-component/pull/443/commits/f3a8f04ba6d83874b772a419fe29e5449b3dcea9))
- console组件的异常处理改为由error组件提供的统一处理风格 ([4f47204](https://github.com/swoft-cloud/swoft-component/pull/443/commits/4f472045e3d80165145907331386f46a2912b2e3))
- console组件允许设置禁用命令组([c5a0269](https://github.com/swoft-cloud/swoft-component/pull/454/commits/c5a0269f26ae6b9739401393a48fd3fd91280bbb))
- 在默认的错误处理中，允许设置错误捕获级别。默认级别是 `E_ALL | E_STRICT` ([afff9029](https://github.com/swoft-cloud/swoft-component/pull/443/commits/afff90296d80085acd30b951441774049d0ca2e7))
- 优化 启动ws server时同时启用了http处理功能，信息面板添加提示([83a81170](https://github.com/swoft-cloud/swoft-component/pull/456/commits/83a8117047036da5687fa6dc5236301716dfd59f))
- 优化 启动ws server 并同时添加rpc server启动，信息面板没有显示 rpc server信息([3d1d0d848](https://github.com/swoft-cloud/swoft-component/pull/454/commits/3d1d0d848a8f5ca31a09feb5ee860079f7c018e4))

扩展(Extra):

- 文档添加支持通过google进行搜索
- 新增 apollo 组件
- 新增 consul 组件
- 新增 breaker 组件
- 新增 limter 组件
- 新增 tcp-server 组件

## v2.0.2-beta(2019-06-21)

新增(Enhancement)：

- 新增 `$request->parsedQuery()` 方法 ([ab45089](https://github.com/swoft-cloud/swoft-component/commit/ab450891b126b8d1a76f1eaa5a020fd69b639383))
- 新增 Bean 属性注入基础数据类型(string/int/bool/float/array)，自动根据注释类型转换。([e3d4085](https://github.com/swoft-cloud/swoft-component/pull/424/commits/e3d40856dabd86b85b67a65adae022aeb88f225a))
- 新增 `db()`, 使 Model/Query/DB 支持切库([f3b12c9](https://github.com/swoft-cloud/swoft-component/pull/429/commits/f3b12c9426407b45fad58391bb6da1f87ea25009))
- 新增 `DbSelectorInterface` 用于根据业务自动切库([b36ca03](https://github.com/swoft-cloud/swoft-component/pull/429/commits/b36ca03420607098d7484976a62c3291bf2f2037))
- 新增 Http server 增加Cookies响应支持 ([1a024bf](https://github.com/swoft-cloud/swoft-component/pull/426/commits/1a024bf5b9371444bcf59df4e960404bc2c99068))
- 新增 `devtool` 组件的实体生成([14b4d39c](https://github.com/swoft-cloud/swoft-devtool/commit/14b4d39c653823f3891343302b3903f032e00760))
- 新增 允许注册 swoole 的 pipeMessage, packet 事件[[afec0e3f](https://github.com/swoft-cloud/swoft-component/pull/438/commits/afec0e3f02a953f386324743e484931af46ebb45)]
- 新增 添加更多启动时的相关事件[[8aaa38c4](https://github.com/swoft-cloud/swoft-component/pull/438/commits/8aaa38c42dbf3d2be8f57dbda5c4431a6907b7c5)]
- 新增 `validate()` 不依赖注解验证方式([cc35560](https://github.com/swoft-cloud/swoft-component/pull/439/commits/cc35560ae54dd66e3ab31298cc3630aa1cdf4633))
- 新增 RPC 调用返回错误 `RpcResponseException` 异常([baa482f](https://github.com/swoft-cloud/swoft-component/pull/440/commits/baa482fdb71287f526e740730e2ea2d98bd775d3))
- 新增 允许注册 swoole 的 pipeMessage, packet 事件([afec0e3f](https://github.com/swoft-cloud/swoft-component/pull/438/commits/afec0e3f02a953f386324743e484931af46ebb45))
- 新增 添加更多启动时的相关事件([8aaa38c4](https://github.com/swoft-cloud/swoft-component/pull/438/commits/8aaa38c42dbf3d2be8f57dbda5c4431a6907b7c5))

修复(Fixed)：

- 修复 `paginate` 无法指定查询字段([308d330](https://github.com/swoft-cloud/swoft-component/pull/421/commits/308d330021df702228cab3140ad2d3bb80f5463f))
- 修复 实体 `join` 操作，显示不出关联表数据 ([446a3a2](https://github.com/swoft-cloud/swoft-component/pull/422/commits/446a3a240e3f671a8a635ae08be05a248b528f6d))
- 修复 实体属性名`attributes`等与系统定义属性冲突问题 ([1a9d25b](https://github.com/swoft-cloud/swoft-component/pull/423/commits/1a9d25b0166b4fdff1eb49fd57a28c3dfe598591))
- 修复 AOP 切面重复执行问题 ([54e00ac](https://github.com/swoft-cloud/swoft-component/commit/54e00aca93d8d2d48ce6ec6a710457beaecf8e58))
- 修复 Http 服务全局异常 `contentType` 失效问题([e704116](https://github.com/swoft-cloud/swoft-component/pull/425/commits/e7041164da51af60b9f89bfbd4110d92eb8d9612))
- 修复 Http server在启用https 时，type 检查无法通过 ([3e9b431](https://github.com/swoft-cloud/swoft-component/pull/426/commits/3e9b431f4de5cdbff9d8898062cf3cabf0e76770))
- 修复 Http server通过data响应html时可能出现错误([e5513df](https://github.com/swoft-cloud/swoft-component/pull/426/commits/e5513df43ec7cec3ef7b45c4ff443e13575c5844))
- 修复 控制器 table 数据显示 ([4d27718](https://github.com/swoft-cloud/swoft-component/pull/426/commits/4d277189f07903c91415d5045c3d9d3605c8f9f8))
- 修复 devtool 生成的 phpstorm meta文件key重复([1838552](https://github.com/swoft-cloud/swoft-devtool/commit/1838552812b8c3220a3bb2ae161a8ed93250498b))
- 修复 ws-server 参数路由匹配失败([4d3392e6](https://github.com/swoft-cloud/swoft-component/pull/426/commits/4d3392e61ec161ebfe07f9bb6e23c2498cc5cf05))
- 修复 Http 服务 content-type 多值解析错误问题([1657979](https://github.com/swoft-cloud/swoft-component/pull/429/commits/165797970e69cdc4da64645583efef3c8eebede1))
- 修复 Co::multi 抛出异常调用错误问题([d0ff0f7](https://github.com/swoft-cloud/swoft-component/pull/429/commits/d0ff0f78cd4105cdcc41653d07c054597d5675ed))
- 修复 body 字符串无解析调用错误问题([32abf60](https://github.com/swoft-cloud/swoft-component/pull/429/commits/32abf60df7aa748bff81b7ed8541545e07a8ba3f))
- 修复 通过 `setter`操作实体 `toArray`失效问题([9c0f9505](https://github.com/swoft-cloud/swoft-component/commit/9c0f9505732414149d4222574b3913f333927222))
- 现在停止server(包含Ctrl+C)时总是会删除pid文件，修复没有删除pid文件导致docker重启失败问题([6a96b2c](https://github.com/swoft-cloud/swoft-component/pull/432/commits/6a96b2cf031e68b12ca16cd6bcb4b2263cf2d31f))
- 修复 通过 `@Inject` 注入`Redis`连接池没有释放以及连接池全部空闲判断问题([feac3700](https://github.com/swoft-cloud/swoft-component/pull/431/commits/feac3700fd882bda0e928d041444446c2737e5e9))
- 修复 数据库开启事物多连接池操作错误([3f80474](https://github.com/swoft-cloud/swoft-component/pull/435/commits/3f80474c477c64a558b39fcaa1d065463de37ccb))
- 修复 `init()` 方法中使用 `config()` 函数问题([fe9b92b](https://github.com/swoft-cloud/swoft-component/pull/439/commits/fe9b92ba97964f7e19742036c26e09a56adb0f0a))
- 修复 `getParsedQuery()` 方法初始化问题([8e05836](https://github.com/swoft-cloud/swoft-component/pull/439/commits/8e0583666993d0f6553bb93ef40fad8b603c0251))
- 修复 一些bean会出现重复初始化(例如 `config`)问题 ([f8ef5a9a](https://github.com/swoft-cloud/swoft-component/pull/441/commits/f8ef5a9a8938e103bff77128f7d452103a4b521c))
- 修复 Autoloader 文件与其它composer包文件冲突问题 ([ba19af363](https://github.com/swoft-cloud/swoft-component/pull/438/commits/ba19af36399ae729df8a2a3166fac477faa939e1))

更新(Update):

- 修改模型方法`insert,insertGetId,updateOrInsert`只会插入定义了`@Column`字段([9c0f9505](https://github.com/swoft-cloud/swoft-component/commit/9c0f9505732414149d4222574b3913f333927222))
- 启动swoft时加入冲突扩展相关的运行环境检查([7fa7188f](https://github.com/swoft-cloud/swoft-component/pull/432/commits/7fa7188f8479c23c10dbc3190acbc981974c5f4a))
- 调整 `count,sum,avg,max,min` 返回值类型([c9b5e17a](https://github.com/swoft-cloud/swoft-component/pull/431/commits/c9b5e17acc970b8a4d9be1a6e6539f09dfe13430))
- Http server 路由匹配顺序优化([d8fb588](https://github.com/swoft-cloud/swoft-component/pull/435/commits/d8fb588d2c49e3dedffd3bc58580c947b37c4471))
- 完善 RPC Client 调用错误信息提示([a1e56eb](https://github.com/swoft-cloud/swoft-component/pull/435/commits/a1e56ebbf061498632e2b21c299c8ab0fa74b117))
- 调整 Redis `zAdd` 成员和分数的位置⚠️不向下兼容([1a3275b3](https://github.com/swoft-cloud/swoft-component/pull/436/commits/1a3275b3b2b1da144de4fe073bdfbe5c43ba7a80))
- 调整 如果没有添加验证注解，会跳过验证解析相关流程([582c806cc](https://github.com/swoft-cloud/swoft-component/pull/438/commits/582c806cccd72383b1e6763885fcc235551b647f))
- 去除swoole中已经废弃的 buffer full/empty 事件相关代码 ([873a97bed](https://github.com/swoft-cloud/swoft-component/pull/438/commits/873a97bedfbed87ac0205be08b6d0534fc3f0558))
- 优化 Http server 验证器流程([0e73a8c](https://github.com/swoft-cloud/swoft-component/pull/439/commits/0e73a8ccd0778812e90dffd34ecdc922a696971c))

扩展(Extra):

- `swoft/view` 添加通过 `@View` 渲染视图支持

## v2.0.1-beta (2019-05-29)

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

## v2.0.0-beta (2019-05-21)

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






