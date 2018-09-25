# AR快速操作
Model里面提供了常见的数据库操作方式。

## 插入数据

### 对象方式

```php
$user = new User();
$user->setName('name');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));
$id  = $user->save()->getResult();
```

### 数组填充

```php
$data = [
    'name' => 'name',
    'sex'  => 1,
    'desc' => 'desc2',
    'age'  => 100,
];

$user   = new User();
$result = $user->fill($data)->save()->getResult();
```

### 数组方式

```php
$user         = new User();
$user['name'] = 'name2';
$user['sex']  = 1;
$user['desc'] = 'this my desc9';
$user['age']  = 99;
$result = $user->save()->getResult();
```

### 批量插入

```php
$values = [
    [
        'name'        => 'name',
        'sex'         => 1,
        'description' => 'this my desc',
        'age'         => 99,
    ],
    [
        'name'        => 'name2',
        'sex'         => 1,
        'description' => 'this my desc2',
        'age'         => 100,
    ]
];

$result = User::batchInsert($values)->getResult();
```

## 删除数据

### 对象删除
 
```php
/* @var User $user */
$user   = User::findById($id)->getResult();
$result = $user->delete()->getResult();
$this->assertEquals(1, $result);
```

### 主键删除一条数据  
 
```php
$result = User::deleteById(1)->getResult();
```

### 主键删除多条数据 
 
```php
$result = User::deleteByIds([1,2])->getResult();
```

### 删除一条数据    

```php
// delete from user where name='name2testDeleteOne' and age=99 and id=1 limit 1
$result = User::deleteOne(['name' => 'name2testDeleteOne', 'age' => 99, 'id' => 1])->getResult();
```

### 删除多条数据

```php
// delete from user where name='name' and id in (1,2)
$result = User::deleteAll(['name' => 'name', 'id' => [1,2])->getResult();
```

## 更新数据

### 实体更新   
 
```php
/* @var User $user */
$user = User::findById(1)->getResult();
$user->setName('newName');
$updateResult = $user->update()->getResult();
```

### 更新一条数据   
 
```php
// update user set name='testUpdateOne' where id=1 limit 1
$result = User::updateOne(['name' => 'testUpdateOne'], ['id' => 1])->getResult();
```

### 更新多条数据  
 
```php
// update user set name='testUpdateOne' where id in (1,2)
$result = User::updateAll(['name' => 'testUpdateAll'], ['id' => [1,2]])->getResult();
```

## 查询数据

使用AR实体查询，返回结果是都是实体对象，不是数组。

### 查询一条数据   
 
```php
// select id,name from user where id=1 limit 1
$user2 = User::findOne(['id' => 1], ['fields' => ['id', 'name']])->getResult();
```

### 查询多条数据

```
findAll(array $condition = [], array $options = [])
```

- `$condition` 查找条件，数组
- `$options` 额外选项。 如： `orderby` `limit` `offset`

使用示例：

```php
// select * from user where name='testUpdateAll' and id in (1,2)
$result = User::findAll(['name' => 'testUpdateAll', 'id' => [1,2]])->getResult();

// select * from user where name='tom' and id > 2 order by createAt DESC
$result = User::findAll(['name' => 'tom', ['id', '>', 2]], ['orderby' => ['createAt' => 'DESC'])->getResult();

// select * from user where name like '%swoft%' order by createAt DESC limit 10
$result = User::findAll([['name', 'like', '%swoft%']], ['orderby' => ['createAt' => 'DESC'], 'limit' => 10)->getResult();
```

### 主键查询一条数据  
 
```php
// selet * from user where id=1
/* @var User $user */
$user = User::findById(1)->getResult();
```

### 主键查询多条数据 
 
```php
// select id from user where id in(1,2) order by id asc limit 0,2
$users = User::findByIds([1,2], ['fields' => ['id'], 'orderby' => ['id' => 'asc'], 'limit' => 2])->getResult();
```

### 实体查询器   
 
```php
// select * from user order by id desc limit 0,2
$result = User::query()->orderBy('id', QueryBuilder::ORDER_BY_DESC)->limit(2)->get()->getResult();
```

### 主键是否存在查询  
 
存在返回true,不存在返回false

```php
User::exist(1)->getResult()
```

### 计数查询

直接返回满足条件的行数
 
```php
$count = User::count('id', ['id' => [1,2]])->getResult();
```
