## 基础查询

swoft提供一套基础查询，类ActiveRecord方法，方便快捷的实现数据库操作，但是实体必须继承Model类，且不能使用事务，如需使用事务，请使用高级查询。基础查询提供延迟收包和非延迟收包两种方式，非延迟收包一般用于并发使用。

## 新增

```php
$user = new User();
$user->setName("stelin");
$user->setSex(1);
$user->setDesc("this my desc");
$user->setAge(mt_rand(1, 100));

// 直接返回结果
//$result = $user->save();

// 延迟操作
$dataResult = $user->save(true);
$deferResult = $dataResult->getResult();
```

# 删除

**实体删除**

```php
$user = new User();
$user->setAge(126);

// 直接返回结果
// $result = $user->delete();

// 延迟操作
$defer = $user->delete(true);
$result = $defer->getResult();
```

**ID删除**

```php
// 直接返回结果
// $result = User::deleteById(284);

// 延迟操作
$deferResult = User::deleteById(287, true);
$result = $deferResult->getResult();
```

**IDS删除**

```php
// 直接返回结果
// $result = User::deleteByIds([291, 292]);

// 延迟操作
$deferResult = User::deleteByIds([288, 289], true);
$result = $deferResult->getResult();
```

## 修改

```php
$query = User::findById(285);

/* @var User $user */
$user = $query->getResult(User::class);
$user->setName("upateNameUser2");
$user->setSex(0);

// 直接返回结果
$result = $user->update();

// 延迟操作
//$result = $user->update(true);
//$result = $result->getResult();
```

## 查询

**实体查询**

```php
$user = new User();
$user->setSex(1);
$user->setAge(93);
$query = $user->find();

// 直接返回数组结果
// $result = $query->getResult();

// 直接返回User实体对象结果
/* @var User $userResult */
// $userResult = $query->getResult(User::class);

// 延迟操作处理
$defer = $query->getDefer();
// $result = $defer->getResult();

$result = $defer->getResult(User::class);

// sql语句
$ql = $query->getSql();
var_dump($result);
```





