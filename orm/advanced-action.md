# 高级查询

高级查询可以处理事务和执行原生SQL语句，同一个实体管理器使用同一个数据库连接。使用基本上基础的查询差不多。

> EntityManager::create\(\)创建一个实体管理，默认是从节点,不存在从节点，使用主节点
>
> 实体管理器使用完，一定要关闭，否则无法释放连接

## 新增

```php
$user = new User();
$user->setName("stelin");
$user->setSex(1);
$user->setDesc("this my desc");
$user->setAge(mt_rand(1, 100));

$em = EntityManager::create(true);
// $result = $em->save($user);
$defer = $em->save($user, true);
$result = $defer->getResult();
$em->close();
```

## 删除

**实体删除**

```php
$user = new User();
$user->setId(418);

$em = EntityManager::create(true);
// $result = $em->delete($user);
$result = $em->delete($user, true);
$em->close();
```

**ID删除**

```php
$em = EntityManager::create(true);
// $result = $em->deleteById(Count::class, 396);
$result = $em->deleteById(Count::class, 406, true);
$em->close();
```

**IDS删除**

```php
$em = EntityManager::create(true);
// $result = $em->deleteByIds(Count::class, [409, 410]);
$result = $em->deleteByIds(Count::class, [411, 412], true);
$em->close();
```

## 更新

```php
// 更是操作略有不同
$em = EntityManager::create();
$query = $em->createQuery()->update(User::class)->set('name', 'new name')->set('age', 12)->where('id', 12);
$result = $query->getResult();
$em->close();
```

## 查询

**实体查询**

```php
$user = new User();
$user->setSex(1);
$em = EntityManager::create();
$query = $em->find($user);
// $result = $query->getResult();
// $result = $query->getResult(User::class);
// $result = $query->getDefer()->getResult();
$result = $query->getDefer()->getResult(User::class);
$sql = $query->getSql();
$em->close();
```

**ID查询**

```php
$em = EntityManager::create();
$query = $em->findById(User::class, 396);
// $result = $query->getResult();
// $result = $query->getResult(User::class);
$result = $query->getDefer()->getResult();
$sql = $query->getSql();
$em->close();
```

**IDS查询**

```php
$query = User::findByIds([416, 417]);

$sql = $query->getSql();

// $defer = $query->getDefer();
// $result = $defer->getResult(User::class);

$result = $query->getResult();
```

**查询器**

```php
// 查询器查询
$em = EntityManager::create();
$query = $em->createQuery();
$query->select("*")->from(User::class, 'u')->leftJoin(Count::class, ['u.id=c.uid'], 'c')->whereIn('u.id', [419, 420, 421])
    ->orderBy('u.id', QueryBuilder::ORDER_BY_DESC)->limit(2);
// $result = $query->getResult();
$result = $query->getDefer()->getResult();
$sql = $query->getSql();
$em->close();


// sql查询
$params = [
    ['uid', 419],
    ['uid2', 420],
    ['uid3', 421, Types::INT],
];
$em = EntityManager::create();

// ':'方式传递参数
$querySql = "SELECT * FROM user AS u LEFT JOIN count AS c ON u.id=c.uid WHERE u.id IN (:uid, :uid1, :uid3) ORDER BY u.id DESC LIMIT 2";
$query = $em->createQuery($querySql);

// 单个设置参数
// $query->setParameter('uid', 419);
// $query->setParameter('uid2', 420);
// $query->setParameter('uid3', 421);

// 数组方式指定参数
$query->setParameters($params);

// '?'方式传递参数
// $querySql = "SELECT * FROM user AS u LEFT JOIN count AS c ON u.id=c.uid WHERE u.id IN (?1, ?2, ?3) ORDER BY u.id DESC LIMIT 2";
// $query = $em->createQuery($querySql);
// $query->setParameter(1, 419);
// $query->setParameter(2, 420);
// $query->setParameter(3, 421);

$result = $query->getResult();
$sql = $query->getSql();
$em->close();
```

## 事务

```php
$user = new User();
$user->setName("stelin");
$user->setSex(1);
$user->setDesc("this my desc");
$user->setAge(mt_rand(1, 100));

$count = new Count();
$count->setFans(mt_rand(1, 1000));
$count->setFollows(mt_rand(1, 1000));

$em = EntityManager::create();
$em->beginTransaction();
$uid = $em->save($user);
$count->setUid($uid);

$result = $em->save($count);
if ($result === false) {
    $em->rollback();
} else {
    $em->commit();
}
$em->close();
```



