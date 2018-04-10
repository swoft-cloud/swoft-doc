# 查询器使用

## 插入数据

```php
$values = [
    'name'        => 'name',
    'sex'         => 1,
    'description' => 'this my desc',
    'age'         => 99,
];
$result = Query::table(User::class)->insert($values)->getResult();
```

## 删除数据

```php
$result = Query::table(User::class)->where('id', 1)->delete()->getResult();
```

## 更新数据

```php
$result = Query::table(User::class)->where('id', 1)->update(['name' => 'name666'])->getResult();
```

## 查询数据

```php
$result = Query::table(User::class)->where('id', 1)->limit(1)->get()->getResult();
```

## 聚合操作

```php
$count    = Query::table(User::class)->count('id', 'userCount')->getResult();
$countNum = $count['userCount'];

$ageNum    = Query::table(User::class)->sum('age', 'ageNum')->getResult();
$ageNum = $ageNum['ageNum'];

$maxAge = Query::table(User::class)->max('age', 'maxAge')->getResult();
$maxAge = $maxAge['maxAge'];

$minAge    = Query::table(User::class)->min('age', 'minAge')->getResult();
$minAge = $minAge['minAge'];

$avgAge = Query::table(User::class)->avg('age', 'avgAge')->getResult();
$avgAge = $avgAge['avgAge'];
```

## 切换数据库实例

```php
$data   = [
    'name'        => 'name',
    'sex'         => 1,
    'description' => 'this my desc instance',
    'age'         => mt_rand(1, 100),
];
$userid = Query::table(User::class)->selectInstance('other')->insert($data)->getResult();
$user2 = Query::table(User::class)->selectInstance('other')->where('id', $userid)->limit(1)->get()->getResult();
```

## 切换数据库

```php
$data   = [
    'name'        => 'name',
    'sex'         => 1,
    'description' => 'this my desc table',
    'age'         => mt_rand(1, 100),
];
$userid = Query::table(User::class)->selectDb('test2')->insert($data)->getResult();
$user2 = Query::table(User::class)->selectDb('test2')->where('id', $userid)->limit(1)->get()->getResult();
```
