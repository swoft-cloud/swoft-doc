# 基础查询
类似ActiveRecord方式操作数据库

## 操作列表

### save

**save(string $group = Pool::GROUP)**

新增数据，返回ResultInterface接口。接口数据，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false

- $group 默认master配置，可以指定使用主还是从，也可以指定其它实例. $group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$user = new User();
$user->setName('stelin');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));
$deferUser = $user->save();

$count = new Count();
$count->setUid(999);
$count->setFans(mt_rand(1, 1000));
$count->setFollows(mt_rand(1, 1000));
$deferCount = $count->save();

// 并发操作
$userResult  = $deferUser->getResult();
$countResult = $deferCount->getResult();

$user = new User();
$user->setName('stelin2');
$user->setSex(1);
$user->setDesc('this my desc2');
$user->setAge(mt_rand(1, 100));
$directUser = $user->save()->getResult();
```

### fill

**fill(array $attributes)**   

数组方式设置属性值，兼容setXxx操作，注意数组Key，必须和实体成员名称一致。

```php
$attrs = [
    'name' => 'stelin3',
    'sex'  => 1,
    'desc' => 'this is my desc2',
    'age'  => 99,
];
$user  = new User();
$user->fill($attrs);
$result = $user->save()->getResult();
```



### delete

**delete(string $group = Pool::GROUP)**

删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $group 默认master配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$user = new User();
$user->setAge(126);

$result = $user->delete()->getResult();
```

### deleteById

**deleteById($id, string $group = Pool::GROUP)**   

根据ID删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $id 删除ID
- $group 默认master配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$result = User::deleteById(287)->getResult();
```

### deleteByIds

**deleteByIds(array $ids, string $group = Pool::GROUP)**   

根据IDS 批量删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $ids 删除ID集合
- $group 默认master配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$result = User::deleteByIds([288, 289])->getResult();
```

### update

**update(string $group = Pool::GROUP)**   

更新数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $group 默认master配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$query = User::findById(285);

/* @var User $user */
$user = $query->getResult(User::class);
$user->setName('upateNameUser2');
$user->setSex(0);

$result = $user->update()->getResult();
```


### find

**find(string $group = Pool::GROUP)**   

查询数据，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $group 默认slave配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$user = new User();
$user->setSex(1);
$user->setAge(93);
$query = $user->find();

$result = $query->getResult(User::class);
```


### findById

**findById($id, string $group = Pool::GROUP)**   

根据ID查询，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $id 查询ID
- $group 默认slave配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$result = User::findById(425)->getResult();

$query = User::findById(426);
/* @var User $user */
$user = $query->getResult(User::class);
$name = $user->getName();
```


### findByIds

**findByIds(array $ids, string $group = Pool::GROUP)**   

根据ID批量查询，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $ids 查询ID集合
- $group 默认slave配置，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$query = User::findByIds([416, 417]);
$result = $query->getResult();

```


### query

**query(string $group = Pool::GROUP): QueryBuilder**  
 
获取该表的一个查询器，只能操作该表

- $group 默认查询使用从库，所有更新操作使用主库，可以指定使用主还是从，也可以指定其它实例。$group = 'default.master' 强制使用主库，$group = 'default.slave' 强制使用从库

```php
$query = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 429)->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();
$result = $query->getResult();
```
