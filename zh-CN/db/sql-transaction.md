# SQL与事务

## SQL原生语句

```php
// 增删改查操作
$result = Db::query('insert into user(name, sex,description, age) values("' . $name . '", 1, "xxxx", 99)')->getResult();
$result = Db::query('delete from user where id=' . $id)->getResult();
$result = Db::query('update user set name="' . $name . '" where id=' . $id)->getResult();
$result = Db::query('select * from user where id=' . $id)->getResult();

// 参数绑定
$result = Db::query('select * from user where id=:id and name=:name', ['id' => $id, ':name'=>'name'])->getResult();
$result2 = Db::query('select * from user where id=? and name=?', [$id, 'name'])->getResult();
```

## 事务

开启事务后，事务之间的所有操作都是同一个连接，注意不能使用并发操作。

```php
Db::beginTransaction();

$user = new User();
$user->setName('name');
$user->setSex(1);
$user->setDesc('this my desc');
$user->setAge(mt_rand(1, 100));

$userId = $user->save()->getResult();

$count = new Count();
$count->setUid($userId);
$count->setFollows(mt_rand(1, 100));
$count->setFans(mt_rand(1, 100));

$countId = $count->save()->getResult();

Db::commit();
//Db::rollback();
```