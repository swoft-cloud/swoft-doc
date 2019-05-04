
# 运行原生的SQL查询

* 1 [运行查询语句](#运行查询语句)
  * 1.1 [使用命名绑定](#使用命名绑定)
  
* 2 [运行插入语句](#运行插入语句)

* 3 [运行更新语句](#运行更新语句)

* 4 [运行删除语句](#运行删除语句)

* 5 [运行普通语句](#运行普通语句)

# 
一旦配置好数据库连接后，便可以使用 DB 对象运行查询。 DB 为每种类型的查询提供了方法： select，selectOne，update，insert，delete , cursor, statement, affectingStatement 和 unprepared。

## 运行查询语句

```php
 $users = DB::select('select * from `user` where `status` = ?', [1]);  
```
`select` 方法将始终返回一个数组，数组中的每个结果都是一个 = `StdClass` 对象，可以像下面这样访问结果值：

```php
foreach ($users as $user) {
    echo $user->name;
}
```
如果只是查询一条可用 `selectOne` 结果返回是一个 = `StdClass` 对象，
 如果没有查询到数据返回是一个空的`StdClass` 对象。
```php
    $sql= 'select * from `user` where `id` = ?';
    $res = DB::selectOne($sql, [1]);
    if ($res) {
        echo $res->name;
    }
```

如果你最快的方式遍历数据表所有数据 可用使用 `DB::cursor($sql)` 方法
```php
    $sql= 'select * from `user`';
    $res = DB::cursor($sql);
    foreach($res as $user){
       echo $user->name;
    }
```
为什么说是最快的呢，因为底层采用 `yield` 机制获取数据 比 `chunk` 快很多

### 使用命名绑定 

除了使用 `?` 表示参数绑定外，你也可以使用命名绑定来执行一个查询：
```php
$results = DB::select('select * from `user` where `id` = :id', ['id' => 1]);
```

## 运行插入语句

可以使用 DB Facade 的 insert 方法来执行 insert 语句。与 select 一样，该方法将原生 SQL 查询作为其第一个参数，并将绑定数据作为第二个参数：

```php
DB::insert('insert into users (`id`, `name`) values (?, ?)', [1, 'sumi']);
```

## 运行更新语句

update 方法用于更新数据库中现有的记录。该方法返回受该语句影响的行数：

```php
$affected = DB::update('update `user` set `status` = 2 where `name` = ?', ['ovo']);
```

## 运行删除语句

delete 方法用于从数据库中删除记录。与 update 一样，返回受该语句影响的行数：

```php
$deleted = DB::delete('delete from `users`');
```

## 运行普通语句

有些数据库语句不会有任何返回值。对于这些语句，你可以使用 DB 的 statement 方法来运行：

```php
DB::statement('drop table `user`');
```

有些数据库语句如果你想知道执行是否有成功。对于这些语句，你可以使用 DB 的 unprepared 方法来运行：

```php
$unprepared = DB::unprepared('DROP TRIGGER IF EXISTS `sync_to_item_table`');
if ($unprepared) {
    // Todo something...
}
```

