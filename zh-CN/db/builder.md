#查询构造器

* 1 [简介](#简介)  
* 2 [获取结果](#获取结果)
  * 2.1 [分块结果](#分块结果)
  * 2.2 [聚合](#聚合)
* 3 [查询](#查询)
* 4 [原生表达式](#原生表达式)
* 5 [Joins](#Joins)
* 6 [Where](#Where语句)
* 7 [Ordering, Grouping, Limit, & Offset](#Ordering,Grouping,Limit,Offset)
* 8 [条件语句](#条件语句)
* 9 [插入](#插入)
* [关于连接释放](#连接何时释放)

## 简介

Swoft 的数据库查询构造器为创建和运行数据库查询提供了一个方便的接口。它可用于执行应用程序中大部分数据库操作，且可在所有支持的数据库系统上运行。

Swoft 的查询构造器使用 `PDO` 参数绑定来保护您的应用程序免受 SQL 注入攻击。因此没有必要清理作为绑定传递的字符串。

你可以使用 `DB::table('xxxx')`得到一个 `Builder` 对象 也可以使用 
`Builder::new()->from('xxx')` 这两种写法返回结果是一样的，

## 获取结果

**从一个数据表中获取所有行**

你可以 DB 上使用 table 方法来开始查询。该 table 方法为给定的表返回一个查询构造器实例，允许你在查询上链式调用更多的约束，最后使用 get 方法获取结果：
```php
// sql = select * from `user`
$users = DB::table('user')->get();
```

该 `get` 方法返回一个包含 `Collection` 的结果，其中每个结果都是 `StdClass` 对象的一个实例。你可以访问字段作为对象的属性来访问每列的值：
```php
foreach ($users as $user) {
    echo $user->name;
}
```
你还可以使用 `Collection` 所有方法 `Collection` 提供了强大的对象操作方法。

如果想更快的查询全部的数据可以用`cursor`方法，底层采用 `yield` 实现。其中每个结果都是 `StdClass` 对象的一个实例
```php
 $users = DB::table('user')->cursor();
 foreach ($users as $user){
    echo $user->name;
 }
```
**从数据表中获取单行或单列**

如果你只需要从数据表中获取一行数据，你可以使用 `first` 方法。该方法返回一个 `StdClass` 对象：
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
### 分块结果
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
闭包里面传递的 `$users` 是对 [get](#获取结果)方法的封装 ，返回是一个`Collection`对象，`each`方法也是通过 `chunk` 实现的 只是参数不同位置相反。

### 聚合
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
如果没有查询到任何数据 返回值是一个 `null`。`avg`是`average`方法的别名，而已返回是一个 float 类型。

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
$users = $query->addSelect('age')->get();
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

orderByRaw 方法可用于将原生字符串设置为 ,order by 子句的值：

```php
$time = time();
$orderBy = 'if(`dead_time`>' . $time . ', update_time,0) desc,create_time desc'; 
       
$orders = DB::table('ticket')
                ->orderByRaw($orderBy)
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
$users = DB::table('sizes')
            ->crossJoin('colours')
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
                   ->select('MAX(created_at) as last_post_created_at')
                   ->where('is_published', true)
                   ->groupBy('user_id');

$users = DB::table('users')
        ->joinSub($latestPosts, 'latest_posts', function($join) {
            $join->on('users.id', '=', 'latest_posts.user_id');
        })->get();

```

**Unions**

查询构造器还提供了将两个查询 「联合」 的快捷方式。比如，你可以先创建一个查询，然后使用 union 方法将其和第二个查询进行联合：
```php

Builder::new()
    ->from('user')
    ->unionAll(function (Builder $builder) {
        $builder->from('user');
    })
    ->union(Builder::new()->from('user'))
    ->get();
            
```
> 提示: 你也可以使用 unionAll 方法，用法 union 方法是的一样。
  
## Where语句

### 简单的 Where 语句

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
$users = DB::table('users')
                ->where('money', '>=', 100)
                ->get();

$users = DB::table('users')
                ->where('money', '<>', 100)
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
### Or 语句

你可以一起链式调用 where 约束，也可以在查询中添加 or 字句。 orWhere 方法和 where 方法接收的参数一样：

```php
$users = DB::table('user')
                    ->where('money', '>', 100)
                    ->orWhere('name', 'John')
                    ->get();
```

### 其他 Where 语句
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
                    ->whereNull('created_at')
                    ->get();
```

`whereNotNull` 方法验证指定的字段必须不是 `NULL`:

```php
$users = DB::table('users')
            ->whereNotNull('created_at')
            ->get();
```

**whereDate / whereMonth / whereDay / whereYear / whereTime**

`whereDate` 方法用于比较字段值与给定的日期:

```php
 $users = DB::table('users')
                ->whereDate('created_at', '2018-09-08')
                ->get();
```

`whereMonth` 方法用于比较字段值与一年中指定的月份:
```php
$users = DB::table('users')
                ->whereMonth('created_at', '9')
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

**参数分组**

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
>tip: 你应该用 `orWhere` 调用这个分组，以避免应用全局作用出现意外.
 
###  Where Exists 语句

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

### JSON Where 语句

`Swoft` 也支持查询 `JSON` 类型的字段（仅在对 `JSON` 类型支持的数据库上）。目前，本特性仅支持 `MySQL 5.7`+。使用 -> 操作符查询 `JSON` 数据：

```php
$users = DB::table('users')
                ->where('options->language', 'en')
                ->get();

$users = DB::table('users')
                ->where('preferences->dining->meal', 'cookie')
                ->get();
```
你也可以使用 whereJsonContains 来查询 JSON 数组：

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

`latest` 和 `oldest` 方法可以使你轻松地通过日期排序。它默认使用 created_at 列作为排序依据。当然，你也可以传递自定义的列名：

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
**自增 ID**

如果数据表有自增 ID ，使用 `insertGetId` 方法来插入记录并返回 ID 值

```php
$id = DB::table('user')->insertGetId([
    'age'  => 18,
    'name' => 'Sakura',
]);
```

## 连接何时释放
