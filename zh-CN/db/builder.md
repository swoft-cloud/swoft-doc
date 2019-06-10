# 查询构造器

## 简介

Swoft 的数据库查询构造器为创建和运行数据库查询提供了一个方便的接口。它可用于执行应用程序中大部分数据库操作，且可在所有支持的数据库系统上运行。

Swoft 的查询构造器使用 `PDO` 参数绑定来保护您的应用程序免受 SQL 注入攻击。因此没有必要清理作为绑定传递的字符串。

你可以使用 `DB::table('xxxx')`得到一个 `Builder` 对象 也可以使用 
`Builder::new()->from('xxx')` 这两种写法返回结果是一样的，`Builder`对象不会分配连接，只有执行 `sql` 的时候才会从连接池从获取

## 获取结果

**从一个数据表中获取所有行**

你可以 DB 上使用 table 方法来开始查询。该 table 方法为给定的表返回一个查询构造器实例，允许你在查询上链式调用更多的约束，最后使用 get 方法获取结果：
```php
// sql = select * from `user`
$users = DB::table('user')->get();
```

该 `get` 方法返回一个包含 `Collection` 的结果，其中每个结果都是 `Array`。你可以访问字段作为对象的属性来访问每列的值：
```php
foreach ($users as $user) {
    echo $user->name;
}
```
你还可以使用 `Collection` 所有方法 `Collection` 提供了强大的对象操作方法。

如果想更快的查询全部的数据可以用`cursor`方法，底层采用 `yield` 实现。其中每个结果都是 `Array`
```php
 $users = DB::table('user')->cursor();
 foreach ($users as $user){
    echo $user->name;
 }
```
**从数据表中获取单行或单列**

如果你只需要从数据表中获取一行数据，你可以使用 `first` 方法。该方法返回一个 `Array`：
```php
$user = DB::table('user')->where('name', 'Sakura')->first();
if ($user) {
    echo $user->name;                
}
```
如果你甚至不需要整行数据，则可以使用 value 方法从记录中获取单个值。该方法将直接返回该字段的值：
```php
$name = DB::table('users')->where('name', 'Sakura')->value('name');
```
**获取一列的值**

如果你想获取包含单列值的集合，则可以使用 pluck 方法。在下面的例子中，我们将获取角色表中标题的集合

```php
$titles = DB::table('roles')->pluck('title');

foreach ($roles as $title) {
    echo $title;
}
```
你还可以在返回的集合中指定字段的自定义键值：
```php
$roles = DB::table('users')->pluck('email', 'name');

foreach ($roles as $name => $email) {
    echo $email;
}
```
## 分块结果
如果你需要处理上千条数据库记录，你可以考虑使用 chunk 方法。该方法一次获取结果集的一小块，并将其传递给 闭包 函数进行处理。在修复数据的时候就很适用。例如，我们可以将全部 user 表数据切割成一次处理 100 条记录的一小块：
```php
DB::table('users')->orderBy('id')->chunk(100, function (\Swoft\Stdlib\Collection $users) {
    foreach ($users as $user) {
       echo $user->name;
    }
});
```
你可以通过在 闭包 中返回 false 来终止继续获取分块结果：
```php
DB::table('users')->orderBy('id')->chunk(100, function (\Swoft\Stdlib\Collection $users) {
    // Process the records...
    return false;
});
```
闭包里面传递的 `$users` 是一个`Collection`对象，`each`方法也是通过 `chunk` 实现的 只是参数不同位置相反。

## 聚合
查询构造器还提供了各种聚合方法，比如 `count`, `max`，`min`， `avg`，还有 `sum`。你可以在构造查询后调用任何方法：
```php
$userNum = DB::table('users')->count();
$price   = DB::table('orders')->max('price');
```
当然，你也可以将这些聚合方法与其他的查询语句相结合：
```php
$price = DB::table('orders')
                ->where('status', 1)
                ->avg('price');
```
如果没有查询到任何数据 返回值是一个 int 类型的 `0`。`avg`是`average`方法的别名，而已返回是一个 `float|int` 类型。

> count 固定返回 `int`, `max`,`min`,`avg`,`sum` 这些函数 可能涉及浮点数计算 底层没有强制转换 返回类型为`float|int`这个值是`数据库`返回的值 

**判断记录是否存在**

除了通过 count 方法可以确定查询条件的结果是否存在之外，还可以使用 `exists` 和 `doesntExist` 方法：
```php
 return DB::table('orders')->where('id', 1)->exists();
 
 return DB::table('orders')->where('id', 1)->doesntExist();
```

## 查询
**指定一个 Select 语句**

当然你可能并不总是希望从数据库表中获取所有列。使用 select 方法，你可以自定义一个 select 查询语句来查询指定的字段：
```php
// select `name`, `age` as `user_age` from `user`
$users = DB::table('user')->select('name', 'age as user_age')->get();
```

distinct 方法会强制让查询返回的结果不重复：
```php
$users = DB::table('users')->distinct()->get();
```

如果你已经有了一个查询构造器实例，并且希望在现有的查询语句中加入一个字段，那么你可以使用 addSelect 方法：
```php
$query = DB::table('users')->select('name');
$users = $query->addSelect(['age'])->get();
```

## 原生表达式

有时候你可能需要在查询中使用原生表达式。你可以使用 `selectRaw`方法 创建一个原生表达式：
```php
 // select count(*) as `user_count`, `name` from `user`
 $users = DB::table('user')
                     ->selectRaw('count(*) as `user_count`, `name`'));
                     ->get();
```
> 提示 原生表达式将会被当做字符串注入到查询中，因此你应该小心使用，避免创建 SQL 注入的漏洞。

**whereRaw / orWhereRaw**

whereRaw 和 orWhereRaw 方法将原生的 where
注入到你的查询中。这两个方法的第二个参数还是可选项，值还是绑定参数的数组：
```php
// select `name`, `age` as `user_age` from `user` where age > 18
$users = DB::table('user')
    ->whereRaw('age > :age', ['age' => 18])
    ->select('name', 'age as user_age')
    ->get();
```

**havingRaw / orHavingRaw**

havingRaw 和 orHavingRaw 方法可以用于将原生字符串设置为 having 语句的值：

```php
 $orders = DB::table('user')
            ->selectRaw('sum(age) as age')
            ->groupBy('user_desc')
            ->havingRaw('age > ?', [17])
            ->get();
```

**orderByRaw**

`orderByRaw` 方法可用于将原生字符串设置为 ,order by 子句的值：

```php
$time = time();
$orderBy = 'if(`dead_time`>' . $time . ', update_time,0) desc,create_time desc'; 
       
$orders = DB::table('ticket')
                ->orderByRaw($orderBy)
                ->get();
```

**fromRaw**

`fromRaw` 方法的自定义 `FROM` 关键字参数,比如使用`强制索引`:

```php
  $sql = DB::table('')
            ->select('id', 'name')
            ->fromRaw('`user` force index(`idx_user`)')
            ->get();
```

## Joins

**Inner Join Clause**

查询构造器也可以编写 `join` 方法。若要执行基本的
「内链接」，你可以在查询构造器实例上使用 `join` 方法。传递给 `join` 方法的第一个参数是你需要连接的表的名称，而其他参数则使用指定连接的字段约束。

你还可以在单个查询中连接多个数据表：

```php
$users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
```

**Left Join 语句**

如果你想使用 「左连接」代替「内连接」 ，可以使用 `leftJoin` 方法。`leftJoin` 方法与 `join` 方法用法相同：

```php
$users = DB::table('users')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();
```

**Cross Join 语句**

使用 `crossJoin` 方法和你想要连接的表名做 「交叉连接」。交叉连接在第一个表和被连接的表之间会生成笛卡尔积：

```php
// select * from `user` cross join `count` on `count`.`user_id` = `user`.`id`
$users =Builder::new()
            ->from('user')
            ->crossJoin('count', 'count.user_id', '=', 'user.id')
            ->get();
```

**高级 Join 语句**

你可以指定更高级的 `join` 语句。比如传递一个 闭包 作为 `join` 方法的第二个参数。此 闭包 接收一个
`JoinClause` 对象，从而指定 `join` 语句中指定的约束

```php
DB::table('users')
        ->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
        })
        ->get();
```

如果你想要在连接上使用「where」 风格的语句，你可以在连接上使用 `where` 和 `orWhere` 方法。这些方法会将列和值进行比较，而不是列和列进行比较：

```php
DB::table('users')
        ->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')
                 ->where('contacts.user_id', '>', 5);
        })
        ->get();
```

**子连接查询**

你可以使用 `joinSub`，`leftJoinSub` 和 `rightJoinSub` 方法关联一个查询作为子查询。他们每一种方法都会接收三个参数：子查询，表别名和定义关联字段的闭包：
```php
$latestPosts = DB::table('posts')
                   ->select('MAX(created_at) as last_created_at')
                   ->where('is_published', true)
                   ->groupBy('user_id');
                   
// $latestPosts 是一个 query 对象
$users = DB::table('users')
        ->joinSub($latestPosts, 'latest_posts', function($join) {
            $join->on('users.id', '=', 'latest_posts.user_id');
        })->get();

```

**Unions**

查询构造器还提供了将两个查询 「联合」 的快捷方式。比如，你可以先创建一个查询，然后使用 union 方法将其和第二个查询进行联合：
```php
// (select * from `user`) union all (select * from `user`) union (select * from `user`)
Builder::new()
    ->from('user')
    ->unionAll(function (Builder $builder) {
        $builder->from('user');
    })
    ->union(Builder::new()->from('user'))
    ->get();
            
```
> 提示: 你也可以使用 unionAll 方法，用法 union 方法是的一样。
  
## 简单的 Where 语句

在构造 where 查询实例的中，你可以使用 where 方法。调用 where 最基本的方式是需要传递三个参数：第一个参数是列名，第二个参数是任意一个数据库系统支持的运算符，第三个是该列要比较的值。

例如，下面是一个要验证 「money」 字段的值等于 100 的查询：

```php
$users = DB::table('user')->where('money', '=', 100)->get();
```
为了方便，如果你只是简单比较列值和给定数值是否相等，可以将数值直接作为 where 方法的第二个参数：

```php
$users = DB::table('users')->where('votes', 100)->get();
```
当然，你也可以使用其他的运算符来编写 where 子句：

```php
$users = DB::table('users')
                ->where('votes', '>=', 100)
                ->get();

$users = DB::table('users')
                ->where('votes', '<>', 100)
                ->get();

$users = DB::table('users')
                ->where('name', 'like', 'T%')
                ->get();
```

你还可以传递条件数组到 where 函数中：

```php
$users = DB::table('users')->where([
    ['status', '=', '1'],
    ['subscribed', '<>', '1'],
])->get();
```
混合数组 `where` 形式，数组里面在嵌套一个数组也是可以的
```php
$wheres   = [
    'name' => 'sakuraovq',
    ['status', '>=', 2],
    ['money', '>', 0, 'or']
];
// select * from `user` where (`name` = ? and `status` >= ? or `money` > ?)
$users    = User::where($wheres)->get();
```
## Or 语句

你可以一起链式调用 where 约束，也可以在查询中添加 or 字句。 orWhere 方法和 where 方法接收的参数一样：

```php
$users = DB::table('user')
                    ->where('money', '>', 100, 'or')
                    ->orWhere('name', 'John')
                    ->get();
```

## 其他 Where 语句
**whereBetween**

`whereBetween` 方法验证字段值是否在给定的两个值之间：
```php
$users = DB::table('user')
                    ->whereBetween('money', [1, 100])->get();
```

**whereNotBetween**

`whereNotBetween` 方法验证字段值是否在给定的两个值之外：

```php
$users = DB::table('user')
                    ->whereNotBetween('money', [1, 100])
                    ->get();
```

**whereIn / whereNotIn**

`whereIn` 方法验证字段的值必须存在指定的数组里，:
```php
$users = DB::table('user')
                    ->whereIn('id', [1, 2, 3])
                    ->get();
```
`whereNotIn` 方法验证字段的值必须不存在于指定的数组里:
```php
$users = DB::table('user')
                    ->whereNotIn('id', [1, 2, 3])
                    ->get();
```

**whereNull / whereNotNull**

`whereNull` 方法验证指定的字段必须是 `NULL`:
```php
$users = DB::table('user')
                    ->whereNull('created')
                    ->get();
```

`whereNotNull` 方法验证指定的字段必须不是 `NULL`:

```php
$users = DB::table('users')
            ->whereNotNull('created')
            ->get();
```

**whereDate / whereMonth / whereDay / whereYear / whereTime**

使用这些日期函数要注意 `MySQL` 时区，
`whereDate` 方法用于比较字段值与给定的日期:

```php
 $users = DB::table('users')
                ->whereDate('created', '2018-09-08')
                ->get();
```

`whereMonth` 方法用于比较字段值与一年中指定的月份:
```php
$users = DB::table('users')
                ->whereMonth('created', '9')
                ->get();
```

`whereDay` 方法用于比较字段值与一月中指定的日期:
```php
$users = DB::table('users')
                ->whereYear('created_at', '2018')
                ->get();
```
`whereTime` 方法用于比较字段值与指定的时间（时分秒）:
```php
$users = DB::table('users')
            ->whereTime('created_at', '=', '11:20:45')
            ->get();
```
**whereColumn**

whereColumn 方法用于比较两个字段的值 是否相等:

```php
 $users = DB::table('users')
                ->whereColumn('first_name', 'last_name')
                ->get();
```
你也可以传入一个比较运算符:
```php
$users = DB::table('users')
                ->whereColumn('updated_at', '>', 'created_at')
                ->get();
```
whereColumn 你也可以传递数组 用 and 运算符链接:

```php
$users = DB::table('users')
                ->whereColumn([
                    ['first_name', '=', 'last_name'],
                    ['updated_at', '>', 'created_at']
                ])->get();
```

## 参数分组

有时候你需要创建更高级的 where 子句，例如「where exists」或者嵌套的参数分组。 Swoft 的查询构造器也能够处理这些。下面，让我们看一个在括号中进行分组约束的例子:

```php
DB::table('user')
            ->where('name', '=', 'John')
            ->where(function ($query) {
                $query->where('votes', '>', 100)
                      ->orWhere('title', '=', 'Admin');
            })
            ->get();
```

你可以看到，通过一个 `Closure` 写入`where` 方法构建一个查询构造器 来约束一个分组。这个 `Closure` 接收一个查询实例，你可以使用这个实例来设置应该包含的约束。上面的例子将生成以下 SQL:

```sql
select * from `user` where `name` = 'sakura' and (`money` > 100 or `title` = 'test')
```
 
##  WhereExists

`whereExists` 方法允许你使用 where exists SQL 语句。 `whereExists` 方法接收一个 `Closure` 参数，该 `whereExists` 方法接受一个 `Closure` 参数，该闭包获取一个查询构建器实例从而允许你定义放置在 `exists` 字句中查询：

```php
DB::table('users')
            ->whereExists(function ($query) {
                $query->from('orders')
                      ->whereRaw('orders.user_id = users.id');
            })
            ->get();
```
上述查询将产生如下的 SQL 语句：

```sql
select * from `users`
where exists (
    select * from `orders` where `orders.user_id` = `users.id`
)
```

## JsonWhere

`Swoft` 也支持查询 `JSON` 类型的字段（仅在对 `JSON` 类型支持的数据库上）。目前，本特性仅支持 `MySQL 5.7`+。

使用 `->` 操作符查询 `JSON` 数据：

```php
$users = DB::table('users')
                ->where('options->language', 'en')
                ->get();

$users = DB::table('users')
                ->where('preferences->dining->meal', 'cookie')
                ->get();
```
你也可以使用 `whereJsonContains` 来查询 JSON 数组：

```php
$users = DB::table('users')
                ->whereJsonContains('options->languages', 'en')
                ->get();
```
MySQL 的 `whereJsonContains` 可以支持多个值：

```php
$users = DB::table('users')
                ->whereJsonContains('options->languages', ['en', 'de'])
                ->get();   
```

## Ordering,Grouping,Limit,Offset

**orderBy**

`orderBy` 方法允许你通过给定字段对结果集进行排序。 `orderBy` 的第一个参数应该是你希望排序的字段，第二个参数控制排序的方向，可以是 asc 或 desc：

**latest / oldest**

`latest` 和 `oldest` 方法可以使你轻松地通过日期排序。它默认使用 `created_at` 列作为排序依据。当然，你也可以传递自定义的列名：

```php
$user = DB::table('users')
                ->oldest()
                ->first();
```

**inRandomOrder**

`inRandomOrder` 方法被用来将结果随机排序。例如，你可以使用此方法随机获得一条记录。

```php
$randomUser = DB::table('user')
                ->inRandomOrder()
                ->first();
```
**groupBy / having**

`groupBy` 和 `having` 方法可以将结果分组。 `having` 方法的使用与 `where` 方法十分相似：

```php
$users = DB::table('users')
                ->selectRaw("count(*) count")
                ->groupBy('type')
                ->having('count', '>', "100")
                ->get();
```
你可以向 `groupBy` 方法传递多个参数：
```php
$users = DB::table('users')
                ->groupBy('first_name', 'status')
                ->having('account_id', '>', "100")
                ->get();
```
对于更高级的 `having` 语法，参见 `havingRaw` 方法。

**skip / take**

要限制结果的返回数量，或跳过指定数量的结果，你可以使用 `skip` 和 `take` 方法：

```php
$users = DB::table('users')->skip(10)->take(5)->get();
```
或者你也可以使用 limit 和 offset 方法：
```php
$users = DB::table('users')
                ->offset(10)
                ->limit(5)
                ->get();
```
如果想快速的分页查询 可以使用 `forPage` 方法
```php
// $this->skip(($page - 1) * $size)->take($size); = forPage($page, $size)
DB::table('users')
            ->forPage($page, $size)
            ->get();
```
## 条件语句

有时候你可能想要子句只适用于某个情况为真是才执行查询。例如你可能只想给定值在请求中存在的情况下才应用 where 语句。 你可以通过使用 `when` 方法：

```php
$role = true;

$users = DB::table('users')
                ->when($role, function ($query, $role) {
                    return $query->where('role_id', $role);
                })
                ->get();
```
`when` 方法只有在第一个参数为 `true` 的时候才执行给的的闭包。如果第一个参数为 `false` ，那么这个闭包将不会被执行

你可以传递另一个闭包作为 `when` 方法的第三个参数。 该闭包会在第一个参数为 `false` 的情况下执行。为了说明如何使用这个特性，我们来配置一个查询的默认排序：
```php
$sortBy = null;

$users = DB::table('users')
                ->when($sortBy, function ($query, $sortBy) {
                    return $query->orderBy($sortBy);
                }, function ($query) {
                    return $query->orderBy('name');
                })
                ->get();
```


查询构造器还提供了 insert 方法用于插入记录到数据库中。 insert 方法接收数组形式的字段名和字段值进行插入操作：

## 插入

查询构造器还提供了 insert 方法用于插入记录到数据库中。 insert 方法接收数组形式的字段名和字段值进行插入操作：

```php
DB::table('users')->insert(
    ['email' => 'john@example.com', 'votes' => 0]
);
```
你甚至可以将数组传递给 `insert` 方法，将多个记录插入到表中
```php
DB::table('users')->insert([
    ['email' => 'taylor@example.com', 'votes' => 0],
    ['email' => 'dayle@example.com', 'votes' => 0]
]);

```

### 自增ID
如果数据表有自增 ID ，使用 `insertGetId` 方法来插入记录并返回 ID 值

```php
$id = DB::table('user')->insertGetId([
    'age'  => 18,
    'name' => 'Sakura',
]);
```

## 更新

当然， 除了插入记录到数据库中，查询构造器也可以通过 `update` 方法更新已有的记录。 `update` 方法和 `insert` 方法一样，接受包含要更新的字段及值的数组。你可以通过 `where` 子句对 `update` 查询进行约束：

**更新 JSON 字段**

更新 JSON 字段时，你可以使用 -> 语法访问 JSON 对象中相应的值，此操作只能用于支持 JSON 字段类型的数据库：
```php
DB::table('users')
            ->where('id', 1)
            ->update(['options->enabled' => true]);
```
**自增与自减**

查询构造器还为给定字段的递增或递减提供了方便的方法。此方法提供了一个比手动编写 `update` 语句更具表达力且更精练的接口。

这两种方法都至少接收一个参数：需要修改的列。第二个参数是可选的，用于控制列递增或递减的量：

```php
DB::table('users')->increment('votes');

DB::table('users')->increment('votes', 5);

DB::table('users')->decrement('votes');

DB::table('users')->decrement('votes', 5);
```
你也可以在操作过程中指定要更新的字段：
```php
DB::table('users')->where('id', 1)->increment('votes', 1, ['updated' => 1]);
```
如果你想自定义更新 可以这样：
```php
$res = DB::table('user')->where('id', $id)->update([
            'posts' => DB::raw('`posts` + 1'),
            'views' => Expression::new('`views` + 1'),
            'name'  => 'updated',
       ]);
```
>`DB::raw(xxx)` 等同 `Expression::new(xxx)` 使用这两个方法的时候要预防`SQL`注入

## 删除
查询构造器也可以使用 `delete` 方法从表中删除记录。 在使用 `delete` 前，可以添加 `where` 子句来约束 `delete` 语法：
```php
DB::table('users')->where('votes', '>', 100)->delete();
```
如果你需要清空表，你可以使用 truncate 方法，它将删除所有行，并重置自增 ID 为零：

```php
DB::table('users')->truncate();
```

## 锁
查询构造器也包含一些可以帮助你在 select 语法上实现「悲观锁定」的函数。若向在查询中实现一个「共享锁」， 你可以使用 读锁 `sharedLock` 方法。 共享锁可防止选中的数据列被篡改，直到事务被提交为止 ：

```php
DB::table('users')->where('votes', '>', 100)->sharedLock()->get();
```

或者，你可以使用 写锁 `lockForUpdate` 方法。使用 「update」锁可避免行被其它共享锁修改或选取：
```php
DB::table('users')->where('votes', '>', 100)->lockForUpdate()->get();
```

## 选择连接池
如果有多个连接连接池 ，默认分配的连接是在 `db.pool`默认连接池 里面获取的，如果要获取自己连接池的连接：
```php
// 在 bean 里面配置的连接池名
$poolName = 'pool.order2';
$user = DB::query($poolName)->from('user')->where('id', $id)->get();
```
`DB::query($poolName)`方法获得同样是一个 `Builder` 对象

## 连接何时释放

底层只有在 执行 sql 的时候才会从 DB 连接池中拿连接执行，执行之后会自动释放。`Builder` 对象不在依赖 `Connection`

<p class="tip"> 释放连接: 把连接还给连接池 </p>
