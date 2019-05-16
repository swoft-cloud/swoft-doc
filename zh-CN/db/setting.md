# 数据库：入门
  
## 简介

`Swoft DB` 操作高度兼容 `Laravel` 能使用 原生的 `SQL`、流畅的查询构造器，和 `Eloquent ORM` 在从此与 `DB` 交互变得简单，去掉了复杂的对象关联模型。采用原生 `PDO` 方式连接数据库。

说下为什么这次要采用`PDO` 的原生方式
<div class="tip"> 使用mysqlnd模式的pdo、mysqli扩展会加入Hook监听,如果未启用mysqlnd将不支持协程化 </div>

也即使说 **IO** 操作会被自动转换和 `swoole` 的 `MySQL 协程客户端`一样。让开发变得简单，更贴近传统框架。
### 基础配置

数据库的配置放置在 `app\bean.php`文件中，去掉了繁琐的`.env`文件配置，你可以认为配置的 `db` 是一个 `bean` 对象。
```php
return [
    'db'         => [
       'class'     => Database::class,
       'dsn'       => 'MySQL:dbname=test;host=127.0.0.1',
       'username'  => 'root',
       'password'  => '123456',
       'charset'   => 'utf8mb4',
    ],
];
```
配置方式类似 `yii2` 对象属性注入的方式的配置，可以通过 `\bean('db')` 来获取当前配置的`Database`对象
- class 指定当前 `bean`容器使用哪个一个类 当然你也可以指定自己实现的`database`类
- dsn `PDO`需要使用的连接配置信息
- username 数据登录用户名
- password 数据库登录密码
- charset 数据库字符集

## 属性配置详解

详细配置例子 ：
```php

'db'  => [
    'class'    => Database::class,
    'dsn'      => 'MySQL:dbname=swoft;host=127.0.0.1',
    'username' => 'root',
    'password' => '123456',
    'charset'  => 'utf8mb4',
    'prefix'   => 't_',
    'options'  => [
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
    ],
    'config'   => [
        'collation' => 'utf8mb4_general_ci',
        'strict'    => false,
        'timezone'  => '+8:00',
        'modes'     => 'NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES',
    ],
],
```  
- prefix 表名的公共前缀或后缀。
- options `PDO`属性选项
```php
    /**
     * 默认的PDO连接选项。当然你可以选择替换它 
     *
     * @var array
     */
    $options = [
        \PDO::ATTR_CASE              => \PDO::CASE_NATURAL,
        \PDO::ATTR_ERRMODE           => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_ORACLE_NULLS      => \PDO::NULL_NATURAL,
        \PDO::ATTR_STRINGIFY_FETCHES => false,
        \PDO::ATTR_EMULATE_PREPARES  => false,
        \PDO::ATTR_ERRMODE           => \PDO::ERRMODE_EXCEPTION,
    ];
```
- config `MySQL`的其他配置， 默认驱动仅实现了 `MySQL` 如果需要其他数据库启动请参考[Connector & Connection](#Connector&Connection)            
    - collation 设置指定数据集如何排序。
    - timezone 设置时区设置
    - modes 设置连接模式（可以是一维数组，也可以是英文逗号分割的 modes）
    - strict 设置获取查询以启用严格模式（实际上也是设置modes配置）

### 读写&连接

```php
'db2'  => [
    'class'  => Database::class,
    'charset'  => 'utf8mb4',
    'prefix'   => 't_',
    'options'  => [],
    'config'   => [
       'collation' => 'utf8mb4_general_ci',
       'strict'    => false,
       'timezone'  => '+8:00',
       'modes'     => 'NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES',
    ],
    'writes' => [
        [
            'dsn'      => 'MySQL:dbname=swoft;host=127.0.0.1',
            'username' => 'root',
            'password' => '123456',
        ],
    ],
    'reads'  => [
        [
            'dsn'      => 'MySQL:dbname=swoft;host=127.0.0.1',
            'username' => 'root',
            'password' => '123456',
        ],
    ],
],
```

- writes 主库配置 执行`Create Update Delete` 操作的时候会从**连接池** 随机选择主库节点执行

- reads 从库配置 `Read` 操作使用默认会从**连接池** 随机选择一个从库节点执行 如果想强制查询的主库，那么请在链式操作中使用 `useWritePdo()` 方法即可
 
如果你想公用配置请参考[基础配置](#基础配置)。 默认会覆盖合并`dsn' , 'username' ,'password','charset' ,'prefix'  ,'options', 'config`这些选项配置公用，所以在`writes/reads`中配置变化的值就可以了，你也可以覆盖上层配置。

### 连接池配置
想必用的 1.0 的小伙伴对连接池并不陌生 2.x 对它进行了简化配置 
连接池的好处也不用多少 更好的管理 资源，对数据库的保护

db 的连接是通过 `连接池`创建和释放的，通过`ConnectionManager`类来管理连接，创建的为短链接 操作执行失败会重试一次。

每当调用 `toSql()` 方法或者执行完操作的时候 都会归还连接到连接池中，
默认是连接池 名称是 `db.pool` 使用的 `database` 是`\bean('db')`
也就是[基础配置](#基础配置)配置的，
连接池配置放置在 `app\bean.php`文件中。

<div class="tip"> 每一个 `worker` 都会创建一个同样的连接池。并不是越多越好，参数配置要根据，机器配置和 和`worker` 个数衡量。
 </div>

下面我们看看连接池如何自定义一个连接池

```php
'db.pool2' => [
    'class'       => Pool::class,
    'database'    => \bean('db2'),
    'minActive'   => 10,
    'maxActive'   => 20,
    'maxWait'     => 0,
    'maxWaitTime' => 0,
    'maxIdleTime' => 60,
 ]
```
- class 是默认的 Pool 对象 你可以更具官方的自己继承实现，然后换成自己 的 Pool 类名就可以了
- database 驱动的数据库对象 是[读 & 写连接](#读写&连接)配置的
- minActive 连接池需要维持的连接数
- maxActive 连接池最大保持的连接数
- maxWait   连接池最多等待连接数, 如果没有限制为0(默认)
- maxWaitTime 连接最大等待时间，单位秒，如果没有限制为0(默认)
- maxIdleTime 连接最大空闲时间，单位秒

### Connector&Connection
连接器和连接 的关系是创建连接必备的 
 ### Connector 
`Connector` 主要是用于，根据配置创建真实的 `PDO` 创建连接
 ### Connection
`Connection`主要用于，数据库的语法解析，设置表前缀，获取默认查询语法实例，重连错误判断
 
 `Swoft` 默认仅提供的 MySQL 的`Connector&Connection` 为什么呢。
 因为`swoole`暂且将 [`pdo_pgsql，pdo_ori，pdo_odbc，pdo_firebird`](https://wiki.swoole.com/wiki/page/965.html)
这些 `PDO`扩展加入
 底层 `Hook`。
 
 也就是说使用 `pdo_pgsql，pdo_ori，pdo_odbc，pdo_firebird` 执行的 `IO` 操作不会让出 `CPU 资源`是**同步执行**的，执行期间`协程`不会`上下文切换`
 
 如果想要使用 `pgsql`（你可以用 `Swoole` 的`协程 PgSQL 客户端`）也是可以的 只需实现`Connector`和`Connection`参照 MySQL 的实现方式即可
 
 
## 关于分库分表的一些见解
 
Swoft DB 暂未提供分库和分表 的方案 后续会提供`dbSelect interface` 实现接口来选择不同的database，
目前暂时可以通过配置`db.pool 连接池`和`db 连接`来选择不同数据库。

 ## 关于去掉DB服务化的见解
 
 Swoft 2.x去掉了 服务发现相关的东西，融入框架显得笨重，后续会提供
`dbProvide interface` 来提供获取连接地址接口
