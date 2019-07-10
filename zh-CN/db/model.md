# 模型


## 简介

无论是高级查询还是基础查询，都会需要一个表实体。一个表字段和一个类属性是一一映射，对类的操作相当于对表的操作，该类称为一个实体 
 Swoft 2.x 和 1.x 实体使用简单的许多，兼有`Builder`查询构造器所有的方法，使用和查询构造器的方法一致，只是返回可能变成了一个 实体。
 
## 实体

一个实体类对应一张数据库的表结构，一个实体对象代表了表的一行数据记录

>注意： 实体不能作为属性被注入到任何类, 因为每个实体对象都是不同的数据记录行。实体对象都是在哪用就在哪里创建它。
 
### 实体定义

下面来看一个实体定义例子：
 
```php
<?php declare(strict_types=1);
 
 namespace SwoftTest\Db\Testing\Entity;
 
 use Swoft\Db\Annotation\Mapping\Column;
 use Swoft\Db\Annotation\Mapping\Entity;
 use Swoft\Db\Annotation\Mapping\Id;
 use Swoft\Db\Eloquent\Model;
 
 /**
  * Class User
  *
  * @since 2.0
  *
  * @Entity(table="user",pool="db.pool2")
  */
 class User extends Model
 {
     /**
      * @Id(incrementing=true)
      *
      * @Column(name="id", prop="id")
      * @var int|null
      */
     private $id;
 
     /**
      * @Column(name="password", hidden=true)
      * @var string|null
      */
     private $pwd;
 
     /**
      * @Column()
      *
      * @var int|null
      */
     private $age;
     
 
     /**
      * @return int|null
      */
     public function getId(): ?int
     {
         return $this->id;
     }
 
     /**
      * @param int|null $id
      */
     public function setId(?int $id): void
     {
         $this->id = $id;
     }
 
     /**
      * @return int|null
      */
     public function getAge(): ?int
     {
         return $this->age;
     }
 
     /**
      * @param int|null $age
      */
     public function setAge(?int $age): void
     {
         $this->age = $age;
     }
     
     /**
      * @return string|null
      */
     public function getPwd(): ?string
     {
         return $this->pwd;
     }
 
     /**
      * @param string|null $pwd
      */
     public function setPwd(?string $pwd): void
     {
         $this->pwd = $pwd;
     }
 }
 ```
 也可以使用 devtool 里面的快速生成实体工具 
 
### 注解标签 

#### @Entity

标记一个类是一个实体，有两个参数

- name 定义该实体映射的数据库表名（必填） 
- pool 该实体选择的 连接池默认为 `db.pool` 你可以替换成自己定义的连接池，设计这个参数的目的有 2 个，第一个你可以切换自己定义db 连接池，第二是你可以使用自己实现的数据库驱动，

假如 `User` 表 是 `MySQL` 的， `Count` 表 可以是 `PostSQL` 的使用不同的连接池即可实现。

#### @Column

标记一个列，如果一个列没有定义`@Column`那么查询它将不会显示，这样即使你新增了数据库字段也不会影响生产环境运行。

- name 定义类属性映射的表字段，没该注解标记的属性，不映射(默认为字段名为属性名)
- prop 为字段设置一个别名
> prop 只是为字段设置一个别名，只有在调用`toArray`的时候才会被转换。这样能隐藏数据库真实的字段。使用`where`等子句，需要使用数据库字段。
- hidden 是否隐藏，如果为真那么它 `toArray()` 的时候将会被隐藏，但是不影响你通过 `Getter`获取它，你也可以调用实体的`setVisible`方法将他取消隐藏。
> 说明：所有字段属性，必须要有`getter`和`setter`方法，你可以使用`phpstorm` 快捷键 `ctrl+n`，它会更具属性 快速生成 `getter`和`setter`。

> **注意** 若表字段有下划线，类属性均定义为 `小驼峰` 写法 例： 字段 `user_name`  则属性写为 `$userName`

2.x 去掉了 type 属性 现在会使用 属性上定义的 `@var` 注解定义的第一个类型，决定了返回值类型，底层会强转类型

#### @Id

该注解标明当前类属性对应了数据库表中的主键，必须有这个注解标记，不能设置多个`@Id`注解

- incrementing 是否为递增主键，默认为递增主键。

## 使用实体

### 插入数据

#### 对象方式

```php
$user = User::new();
$user->setName('name');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));
$id  = $user->save();
```

#### 数组方式

```php
$attributes = [
    'name'      => uniqid(),
    'password'  => md5(uniqid()),
    'age'       => mt_rand(1, 100),
    'user_desc' => 'u desc'
];

$result3 = User::new($attributes)->save();
```

#### 批量插入

如果你想批量插入可以使用 `User::insert([])`方法 使用和 查询构造器的 `insert`方法 使用完全一致

### 删除数据

指定 id 删除

```php
$user = User::find($id);
$result = $user->delete();
```

使用条件删除

```php
$result = User::where('id', 1)->delete();
```

删除一条

```php
$result = User::where('stauts',1 )->limit(1)->delete();
```

### 更新数据

#### 实体更新

使用 `setter` 或者`array` 都可以更新

```php
$user = User::find($id);

$name   = uniqid();
$user->setAge(1);

$result = $user->update(['name' => $name]);
```

#### 条件批量更新

更新一条数据

```php
$wheres   = [
    'name' => 'swoft',
    ['id', '>=', 2]
];
$orWheres = [
    ['status', '<>', '1']
];
$result   = User::where($wheres)
                ->limit(1)
                ->orWhere($orWheres)
                ->update(['status' => 1]);
```

#### 更新/插入

可以使用`updateOrCreate` 返回的是一个实体

```php
$user = User::updateOrCreate(['id' => 1], ['age' => 18, 'name' => 'sakuraovq']);
echo $user->getName();
```

也可以用 `updateOrInsert` 返回一个 `bool` 值

```php
$isOk = User::updateOrInsert(['id' => 1], ['age' => 18, 'name' => 'sakuraovq']);
```

### 查询数据

> 模型的查询方法和查询构造器完全兼容

查询一条数据，返回一个实体

```php
// 方法 1
$user =  User::find(1, ['id','name']);
// 方法 2
$user = User::where('id',1)->first();
```

查询多条数据

```php
// 方法 1
$users = User::findMany([1,2,3,4], ['id','name']);
// 方法 2
$useer = User::whereIn('id', [1,2,3,4])->select(['id','name'])->get();
```

如果你想获取的列表中都是 `实体`对象 可以使用 `getModels` 方法，该方法返回的是一个实体数组 你可以这样使用：

```php
$users = User::where('id', 22)->getModels(['id', 'age']);
/* @var User $user */
foreach ($users as $user) {
    $age = $user->getAge();
}
```

有时候我我们需要按数据库某个键作为 `key` 我们逻辑映射关系，可以使用  `Collection` 的  `keyBy` 方法。

假如 需要第一页 数据需要以`id`作为`key` 你可以这样使用：

```php
$users = User::forPage(1, 10)->get(['id', 'age'])->keyBy('id');

/* @var User $user */
foreach ($users as $id => $user) {
    $age = $user->getAge();
}
```

> 实体使用 Join 系列操作，不会返回实体

```php
$userCounts = User::join('count', 'user.id', '=', 'count.user_id')->get();
```

### 分块结果

如果你需要处理数千个 Eloquent 记录，可以使用 `chunk` 命令。`chunk` 方法会检索 Eloquent 模型的「分块」，将它们提供给指定的 `Closure` 进行处理。在处理大型结果集时，使用 `chunk` 方法可节省内存：

```php
 Flight::chunk(200, function ($flights) {
        foreach ($flights as $flight) {
            //
        }
 });
```
   

传递到方法的第一个参数是希望每个「分块」接收的数据量。闭包则被作为第二个参数传递，它会在每次执行数据库查询传递每个块时被调用。


#### 使用游标

`cursor` 允许你使用游标来遍历数据库数据，该游标只执行一个查询。处理大量数据时，可以使用 `cursor` 方法可以大幅度减少内存的使用量：

```php
    foreach (Flight::where('foo', 'bar')->cursor() as $flight) {
        //
    }
```

### 「找不到」异常

如果你希望在找不到模型时抛出异常，可以使用 `findOrFail` 以及 `firstOrFail` 方法。这些方法会检索查询的第一个结果。如果没有找到相应结果，就会抛出一个 `DbException`：

```php
    $model = App\Flight::findOrFail(1);

    $model = App\Flight::where('legs', '>', 100)->firstOrFail();
```

### 赋值
 如果你觉得 `setter` 太麻烦了可以使用,批量填充功能, 使用这种方式要注意如果该字段`没有匹配`到 `@Column` 值将会被忽 这样能保证安全的更新和插入
 ```php
// Properties
    $attributes = [
        'name'      => uniqid(),
        'password'  => md5(uniqid()),
        'age'       => mt_rand(1, 100),
        'user_desc' => 'u desc'
    ];
    // one 
    $result3 = User::new($attributes)->save();
    // two
    $result3 = User::new()->fill($attributes)->save();
```

### 检索集合

你还可以使用 [查询构造器](builder.md) 提供的 `count`、`sum`、`max` 以及其它 聚合函数。这些方法只会返回适当的标量值而不是整个模型实例：

```php
    $count = App\Flight::where('active', 1)->count();

    $max = App\Flight::where('active', 1)->max('price');
```

### 其他创建方法

#### `firstOrCreate`/ `firstOrNew`
你还可以使用其他两种方法来创建模型：`firstOrCreate` 和 `firstOrNew`。`firstOrCreate` 方法会使用给定的字段及其值在数据库中查找记录。如果在数据库中找不到模型，则将使用第一个参数中的属性以及可选的第二个参数中的属性插入记录。

`firstOrNew` 方法就类似 `firstOrCreate` 方法，会在数据库中查找匹配给定属性的记录。如果模型未被找到，则会返回一个新的模型实例。请注意，在这里面，`firstOrnew` 返回的模型还尚未保存到数据库，必须要手动调用 `save` 方法才能保存它：
```php
    // 通过 name 属性检索航班，当结果不存在时创建它...
    $flight = App\Flight::firstOrCreate(['name' => 'Flight 10']);

    // 通过 name 属性检索航班，当结果不存在的时候用 name 属性和 delayed 属性去创建它
    $flight = App\Flight::firstOrCreate(
        ['name' => 'Flight 10'], ['delayed' => 1]
    );

    // 通过 name 属性检索航班，当结果不存在时实例化...
    $flight = App\Flight::firstOrNew(['name' => 'Flight 10']);

    // 通过 name 属性检索航班，当结果不存在的时候用 name 属性和 delayed 属性实例化
    $flight = App\Flight::firstOrNew(
        ['name' => 'Flight 10'], ['delayed' => 1]
    );
```

#### `updateOrCreate`
你也可能会遇到想要更新现有模型或创建新模型（如果不存在）的情况。Swoft 提供了 `updateOrCreate` 方法来完成该操作，像 `firstOrCreate` 方法一样，`updateOrCreate` 方法会保存模型，所以不需要调用 `save()` :

```php
    // 如果有从奥克兰飞往圣地亚哥的航班，将价格设为 99 美元
    // 如果不存在匹配的模型就创建一个
    $flight = App\Flight::updateOrCreate(
        ['departure' => 'Oakland', 'destination' => 'San Diego'],
        ['price' => 99]
    );
```   


更多方法请参照 [查询构造器](builder.md)

## 自动写入时间戳

默认情况下，Eloquent 会默认数据表中存在 `created_at` 和 `updated_at` 这两个字段。如果你不需要这两个字段，则需要在模型内将 `$modelTimestamps` 属性设置为 `false`：

```php
<?php

    namespace App;

    use App\Model\Dao;

    class UserDao extends User
    {
        /**
         * 该模型是否被自动维护时间戳
         *
         * @var bool
         */
        public $modelTimestamps = false;
    }
```    

如果你需要自定义时间戳格式，可在模型内设置 `$modelDateFormat` 属性。这个属性决定了日期属性应如何存储在数据库中，以及模型被序列化成数组或 JSON 时的格式：

```php
<?php
    class UserDao extends User
    {
        /**
         * 模型的日期字段的存储格式
         *
         * @var string
         */
        protected $modelDateFormat = 'Y-m-d H:i:s';
    }
```

如果需要自定义用于存储时间戳的字段名，可在模型中通过设置 `CREATED_AT` 和 `UPDATED_AT` 常量来实现：

> 时间戳 支持 数据库 `int`  和 `timestamp` 类型, 底层会自动根据实体的属性  `CREATED_AT` 和 `UPDATED_AT` 这个两个字段定义的 `@var` 来判断. 用户无需操心生成时间戳格式

```php
<?php
    class UserDao extends User
    {
       protected const CREATED_AT = 'create_time';
       protected const UPDATED_AT = 'update_data';
    }
```  

> 不推荐在实体里面改动,这样方便改动了数据表结构就可以使用自动生成实体来更新. 推荐在 `Dao` 层来对实体扩展 

## 事件

Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控： `creating`、`created`、`updating`、`updated`、`saving`、`saved`、`deleting`、`deleted`。事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。当然你完全可以通过 `AOP` 来实现它

 当新模型第一次被保存时， `creating` 以及 `created` 事件会被触发。如果模型已经存在于数据库中并且调用了 `save` 方法，会触发 `updating` 和 `updated` 事件。在这两种情况下，`saving` / `saved` 事件都会触发。

事件名称是 `swoft.model`+模型名+动作名

- 模型名 是首字母默认会小写 例如实体名称 `SendMessage` 要监听它的`saving` 动作的话 格式就是 `swoft.model.sendMessage.saving` 其他模型也类似.

可以监听某个模型的`saving`操作的动作, 也可以监听所有模型的`saving`动作 

- 监听模型单个动作

```php
<?php declare(strict_types=1);


namespace App\Listener;

use App\Model\Entity\User;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class UserSavingListener
 *
 * @since 2.0
 *
 * @Listener("swoft.model.user.saving")
 */
class UserSavingListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var User $user */
        $user = $event->getTarget();

        if ($user->getAge() > 100) {
            // stopping saving
            $event->stopPropagation(true);

            $user->setAdd(100);
        }
    }
}

```

- 监听所有模型的单个动作

```php
<?php declare(strict_types=1);


namespace App\Listener;

use App\Model\Entity\User;
use Swoft\Db\DbEvent;
use Swoft\Db\Eloquent\Model;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class RanListener
 *
 * @since 2.0
 *
 * @Listener(DbEvent::MODEL_SAVED)
 */
class ModelSavedListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var Model $modelStatic */
        $modelStatic = $event->getTarget();

        if ($modelStatic instanceof User) {
            // to do something....
        }

        // ....
    }
}

```

公共的事件名列表, 可以在`Swoft\Db\DbEvent` 类中参看所有事件

Event  | Params | Description
------------- | ------------- | -------------
`swoft.db.transaction.begin`  | 没有参数 | 事务启动。
`swoft.db.transaction.commit`  | 没有参数 | 事务提交。
`swoft.db.transaction.rollback`  | 没有参数 | 事务回滚。
`swoft.model.saving`  | target 是具体操作实体类 | 所有实体保存中事件。
`swoft.model.saved`  | target 是具体操作实体类 | 所有实体保存后事件。
`swoft.model.updating`  | target 是具体操作实体类 | 所有实体更新前事件。
`swoft.model.updated`  | target 是具体操作实体类 | 所有实体更新后事件。
`swoft.model.creating`  | target 是具体操作实体类 | 所有实体创建前事件。
`swoft.model.created`  | target 是具体操作实体类 | 所有实体创建后事件。
`swoft.model.deleting`  | target 是具体操作实体类 | 所有实体删除前事件。
`swoft.model.deleted`  | target 是具体操作实体类 | 所有实体后删除前事件。
`swoft.db.ran`  | target 是连接对象,参数 1=未预处理 sql ,参数 2=绑定的参数 | 所有 sql 执行后的事件,事件返回的连接已返回给连接池只能获取它的配置信息。
`swoft.db.affectingStatementing`  | target 是连接对象,参数 1=正在处理的` PDO statement` ,参数 2=绑定的参数 | 正在执行 `update` 和`delete`动作
`swoft.db.selecting`  | target 是连接对象,参数 1=正在处理的` PDO statement` ,参数 2=绑定的参数  | 正在执行查询动作。

> 如果是`正在进行时(ing)` 在监听事件中是调用了 `$event->stopPropagation(true);` 后续操作会终止直接返回结果. 对`过去式`停止无效

## FQA

使用模型 使用 `select` 方法最好, 不要使用 `as` 不然查询结果与实体映射可能会有问题

> 使用模型的方法 更新/插入的值 都会过滤处理 没有 定义 `@Column` 的值将会被过滤