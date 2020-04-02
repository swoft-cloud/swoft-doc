# 切换数据库 

在之前的`2.0.2`,之前在一个连接池中没有 提供切库的功能, 导致不同数据库需要配置多个连接池, 这大大增加了维护成本, 所以在 `2.0.2` 提供了切换数据库功能, 你可以在链式操作中手动自定 `db()`方法, 这显然不够灵活难以维护. 

下面推荐一种根据上下文切库的 操作

> Available: `>= v2.0.2`

## DbSelector

```php
<?php declare(strict_types=1);


namespace App\Common;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Connection\Connection;
use Swoft\Db\Contract\DbSelectorInterface;

/**
 * Class DbSelector
 *
 * @since 2.0
 *
 * @Bean()
 */
class DbSelector implements DbSelectorInterface
{
    /**
     * @param Connection $connection
     */
    public function select(Connection $connection): void
    {
        // 在请求中获取 id
        $selectIndex  = (int)context()->getRequest()->query('id', 0);
        $createDbName = $connection->getDb();

        if ($selectIndex == 0) {
            $selectIndex = '';
        }
        // 数据库名 + id  类似这样的 order_database_1, 好处是会根据上下文自动切库
        $dbName = sprintf('%s%s', $createDbName, (string)$selectIndex);
        $connection->db($dbName);
    }
}
```

## DbSelector 配置

<p class="tip">  使用 DbSelector 必须要实现 DbSelectorInterface 接口</p>


>实现了 DbSelector 还没完 还需在`bean.php`文件配置中定义 `dbSelector`属性

例如: 
```php
'db2'        => [
    'class'      => Database::class,
    'dsn'        => 'mysql:dbname=test;host=127.0.0.1',
    'username'   => 'root',
    'password'   => 'swoft123456',
    'dbSelector' => bean(DbSelector::class)
],
'db2.pool'   => [
    'class'    => Pool::class,
    'database' => bean('db2')
],

```

- dbSelector 是上面例子实现的类, 这样每次操作都会调用它 自动切库, 让分库变得简单

使用这种好处是, 你可以像没有分库一样使用, 大大降低了维护成本. 官方推荐使用这种方案

如果不满足这种的话你只能在操作的时候使用 `db()` 方法手动指定数据库了, 这样容错成本会大大降低

```php
// 实体使用方式
 User::db('test2')->insertGetId([
            'name'      => uniqid(),
            'password'  => md5(uniqid()),
            'age'       => mt_rand(1, 100),
            'user_desc' => 'u desc',
            'foo'       => 'bar',
            'xxxx'      => '223asdf'
 ]);
 // DB 使用方式       
 DB::table('user')->db('test2')->insertGetId([
            'name'      => uniqid(),
            'password'  => md5(uniqid()),
            'age'       => mt_rand(1, 100),
            'user_desc' => 'u desc',
 ]);
        
```
