# 模型


## 简介
无论是高级查询还是基础查询，都会需要一个表实体。一个表字段和一个类属性是一一映射，对类的操作相当于对表的操作，该类称为一个实体 
 Swoft 2.x 和 1.x 实体使用简单的许多，兼有`Builder`查询构造器所有的方法，使用和查询构造器的方法一致，只是返回可能变成了一个 实体。
## 实体

一个实体类对应一张数据库的表结构

一个实体对象代表了表的一行数据记录

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
- hidden 是否隐藏，如果为真那么它 `toArray()` 的时候将会被隐藏，但是不影响你通过 `Getter`获取它，你也可以调用实体的`setVisible`方法将他取消隐藏。
> 说明：所有字段属性，必须要有`getter`和`setter`方法，你可以使用`phpstorm` 快捷键 `ctrl+n`，它会更具属性 快速生成 `getter`和`setter`。

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

使用 `setter` 或者`array`都可以更新
```php
$user = User::find($id);

$name   = uniqid();
$user->setAge(1);

$result = $user->update(['name' => $name]);
```
#### 条件更新
更新一条数据
```php
$wheres   = [
    'name' => 'sakuraovq',
    ['id', '>=', 2]
];
$orWheres = [
    ['status', '<>', 1']
];
$result   = User::where($wheres)
                ->limit(1)
                ->orWhere($orWheres)
                ->update(['name' => 'sakuraovq' . mt_rand(1, 10)]);
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
```php\
$userCounts = User::join('count', 'user.id', '=', 'count.user_id')->get();
```
更多方法请参照 查询构造器
