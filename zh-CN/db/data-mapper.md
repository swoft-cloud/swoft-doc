# 高级查询
使用Data mapper 模式操作数据库，只有高级模式才支持数据库事务操作。

## 使用步骤
高级方式操作数据库，使用必须包括三个步骤，缺一不可。

**1. 创建实体管理器**    

首先创建实体管理，打开实体会话。

```php
$em = EntityManager::create();
// ......
```


**2. 数据库操作**    

```php

// ......

$user = new User();
$user->setName('stelin');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));
$result = $em->save($user)->getResult();

// ......

```

**3. 关闭实体管理器**    

关闭实体管理器，既是关闭实体会话，切记一定要关闭，否则数据库连接无法释放，严重影响性能。

```php
// ......
$em->close();
```

## 操作列表

### save

**save($entity)**    

新增数据，返回ResultInterface接口。接口数据，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false

- $entity 填充数据的实体对象

```php
$user = new User();
$user->setName('stelin');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));

$em = EntityManager::create();
$result = $em->save($user)->getResult();
$em->close();

```

# delete

**delete()**    

删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

```php
$user = new User();
$user->setId(418);

$em = EntityManager::create();
$result = $em->delete($user)->getResult();
$em->close();
```

# deleteById

**deleteById($className, $id)**    

根据ID删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $className 实体类名
- $id 删除ID

```php
$em = EntityManager::create();
$result = $em->deleteById(Count::class, 406)->getResult();
$em->close();
```

# deleteByIds

**deleteByIds($className, array $ids)**    
根据IDS 批量删除数据，返回ResultInterface接口。接口数据，成功返回影响行数，如果失败返回false

- $className 实体类名
- $ids 删除ID集合


```php
$em = EntityManager::create();
$result = $em->deleteByIds(Count::class, [411, 412])->getResult();
$em->close();
```

# find

**find($entity)**  
查询数据，返回ResultInterface接口

- $entity 实体对象

```php
$user = new User();
$user->setSex(1);
$em = EntityManager::create();
$query = $em->find($user);
$result = $query->getResult(User::class);
$em->close();
```

# findById

**findById($className, $id)**    

查询数据，返回ResultInterface接口

- $className 实体类名
- 删除ID

```php
$em = EntityManager::create();
$query = $em->findById(User::class, 396);
$result = $query->getResult();
$em->close();
```


# findByIds

**findByIds($className, array $ids)**    

查询数据，返回ResultInterface接口

- $className 实体类名
- $ids 删除ID集合

```php
$em = EntityManager::create();
$query = $em->findByIds(User::class, [396, 403]);
$result = $query->getResult();
$em->close();

```

# createQuery

**createQuery(string $sql = '')**    

查询数据，返回ResultInterface接口

- $sql sql语句，如果为空，返回一个查询器，如果有SQL语句，执行sql语句查询。SQL语句如果参数是站位符号，底层实现是预处理，不用担心SQL注入。

```php

// 查询器
$em = EntityManager::create();
$query = $em->createQuery();
$result = $query->select('*')->from(User::class, 'u')->leftJoin(Count::class, ['u.id=c.uid'], 'c')->whereIn('u.id', [419, 420, 421])
->orderBy('u.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();
$result = $result->getResult();
$sql = $query->getSql();
$em->close();


// sql语句
$em = EntityManager::create();
$querySql = 'SELECT * FROM user AS u LEFT JOIN count AS c ON u.id=c.uid WHERE u.id IN (?, ?, ?) ORDER BY u.id DESC LIMIT 2';
$query = $em->createQuery($querySql);
$query->setParameter(1, 433);
$query->setParameter(2, 434);
$query->setParameter(3, 431);

$result = $query->execute();
$sql = $query->getSql();
$em->close();
```

## 事务处理

EntityManage开启会话，后续执行的AR操作，是支持事务的，但是必须是相同的poolId。

```php
$user = new User();
$user->setName('stelin');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));

$user2 = new User();
$user2->setName('stelin');
$user2->setSex(1);
$user2->setDesc('this my desc');
$user2->setAge(mt_rand(1, 100));

$count = new Count();
$count->setFans(mt_rand(1, 1000));
$count->setFollows(mt_rand(1, 1000));

$em = EntityManager::create();
$em->beginTransaction();

$uid = $em->save($user)->getResult();
$count->setUid($uid);

$result = $em->save($count)->getResult();

// AR操作，这里是支持事务操作的，同一个连接
$result2 = $user2->save()->getResult();


$em->commit();
$em->close();

return [$uid, $result];
```

## 注意事项

- EntityManager 一定要close，否则会浪费连接
- EntityManager 如果没有close， 请求处理成功后，会统一释放连接，但是很浪费，不建议这样操作。