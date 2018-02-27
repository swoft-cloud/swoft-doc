# 基础查询
类似ActiveRecord方式操作数据库

## 操作列表

### save

**save(string $poolId = Pool::MASTER)**

新增数据，返回ResultInterface接口。接口数据，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false

- $poolId 默认master配置，可以指定使用主还是从，也可以指定其它实例

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

### delete

**delete(string $poolId = Pool::MASTER)**

删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $poolId 默认master配置，可以指定使用主还是从，也可以指定其它实例

```php
$user = new User();
$user->setAge(126);

$result = $user->delete()->getResult();
```

### deleteById

**deleteById($id, string $poolId = Pool::MASTER)**   

根据ID删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $id 删除ID
- $poolId 默认master配置，可以指定使用主还是从，也可以指定其它实例

```php
$result = User::deleteById(287)->getResult();
```

### deleteByIds

**deleteByIds(array $ids, string $poolId = Pool::MASTER)**   

根据IDS 批量删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $ids 删除ID集合
- $poolId 默认master配置，可以指定使用主还是从，也可以指定其它实例

```php
$result = User::deleteByIds([288, 289])->getResult();
```

### update

**update(string $poolId = Pool::MASTER)**   

更新数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $poolId 默认master配置，可以指定使用主还是从，也可以指定其它实例

```php
$query = User::findById(285);

/* @var User $user */
$user = $query->getResult(User::class);
$user->setName('upateNameUser2');
$user->setSex(0);

$result = $user->update()->getResult();
```


### find

**find(string $poolId = Pool::SLAVE)**   

查询数据，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $poolId 默认slave配置，可以指定使用主还是从，也可以指定其它实例

```php
$user = new User();
$user->setSex(1);
$user->setAge(93);
$query = $user->find();

$result = $query->getResult(User::class);
```


### findById

**findById($id, string $poolId = Pool::SLAVE)**   

根据ID查询，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $id 查询ID
- $poolId 默认slave配置，可以指定使用主还是从，也可以指定其它实例

```php
$result = User::findById(425)->getResult();

$query = User::findById(426);
/* @var User $user */
$user = $query->getResult(User::class);
$name = $user->getName();
```


### findByIds

**findByIds(array $ids, string $poolId = Pool::SLAVE)**   

根据ID批量查询，返回ResultInterface接口。接口数据，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组

- $ids 查询ID集合
- $poolId 默认slave配置，可以指定使用主还是从，也可以指定其它实例

```php
$query = User::findByIds([416, 417]);
$result = $query->getResult();
```


### query

**query(string $poolId = Pool::SLAVE): QueryBuilder**  
 
获取该表的一个查询器，只能操作该表

- $poolId 默认slave配置，可以指定使用主还是从，也可以指定其它实例

```php
$query = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 429)->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();
$result = $query->getResult();
```
