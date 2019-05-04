#事务

* 1 [开启事务](#开启事务)
   
* 2 [事务回滚](#事务回滚)

* 3 [事务提交](#事务提交)

* 4 [常见问题](#常见问题)
   * 4.1 [事务是否支持嵌套](#事务是否支持嵌套)
   * 4.2 [如果事务没有提交怎么办](#如果事务没有提交怎么办)
* 5 [错误示范](#错误示范)


## 开启事务
 如果你想要开始一个事务，并且对回滚和提交能够完全控制，那么你可以使用 DB  的 `beginTransaction` 方法：

```php
DB::beginTransaction();
```
或者
```php
DB::connection()->beginTransaction();
```
一旦开启了事务，当前连接会绑定到当前的协程环境中，保证`提交`和`回滚`，`查询`都是同一个连接保证数据的安全性，只有`提交`或者`回滚`完毕才会解除绑定。


## 事务回滚
如果操作失败需要回滚 使用下面两种方式都可以

```php
DB::rollBack();
```
或者
```php
DB::connection()->rollBack();
```

## 事务提交
如果操作执行成功需要 使用下面两种方式都可以

```php
DB::commit();
```
或者
```php
DB::connection()->commit();
```

## 常见问题

### 事务是否支持嵌套
MySQL 官方文档说 如果事务发生嵌套，会隐式提交`上一个事务`，然后开启一个新的事务。

框架的答案是可以做`事务嵌套`，因为部分数据库支持 支持 `savepoints` 能力。
在MySQL中, 保存点`savepoints`属于事务控制处理部分。利用`savepoints`可以回滚指定部分事务，从而使事务处理更加灵活和精细。如果你进行了事务嵌套嵌套的事务会保存在`savepoints`里面，可以做嵌套事务的精细控制。
```php
DB::beginTransaction();
$user = User::find($id);
$user->update(['name' => $id]);

DB::beginTransaction();
User::find($id)->update(['name'=>'sakuraovq']);
DB::rollBack();

DB::commit();
```
`嵌套`里面的的事务进行了`回滚`，不会影响`外层`改变的数据只回滚name=sakuraovq的修改，这段代码执行下来 name = $id

### 如果事务没有提交怎么办
```php
DB::connection()->beginTransaction();
$user = User::find($id);

\sgo(function ()use($id) {
    DB::connection()->beginTransaction();
    User::find($id);
});
```
类似这样的代码如果我们忘记 提交事务/回滚。

Swoft 在`SwoftEvent::COROUTINE_DEFER`事件中会检查是否还处于事务状态，如果是会自动 `rollback`到最初开启事务的状态。正常的连接会归还到连接池中，不会造成资源泄露。

### 错误示范
```php
DB::beginTransaction();
$user = User::find($id);

\sgo(function () use ($id) {
    $user1 = User::find($id);
});
$user->update(['name' => 'sakuraovq'.mt_rand(110,10000)]);
DB::commit();

```
类似这样的代码虽然执行没有问题，这种写法是错误的，会造成数据的错乱。**请不要在事务中嵌套`协程`，在执行 db 操作**, 上文讲到事务是绑定到`当前协程`的 切换了协程也就是另一个新的连接。

```php
DB::connection()->beginTransaction();
$user = User::find($id);
$user->update(['name' => 2]);

\sgo(function () use ($user) {
    $user->update(['name' => 1]);
});
DB::rollBack();
```
这样也是错的，这段代码执行下来你会发现，子协程的修改操作回滚**不受控制**。因为它们使用了不同的连接，并非绑定在一起。
