# Table

Swoft-Table基于Swoole的table实现，是一个基于共享内存和锁实现的超高性能，并发数据结构。

# 注意事项

- 不受PHP的memory_limit控制

- 应用代码无需加锁，内置行锁自旋锁，所有操作均是多线程/多进程安全。

- 支持多进程，swoole_table可以用于多进程之间共享数据（前提需要在workStart回调函数之前启动，否则进程隔离）

## 创建swoft-table对象

`Table(string $name = '',int $size = 0, $column = [])`


## $column 说明

$column 是二维数组，结构如下：

```php

  /**
   * @var array $column 列数组
   * [
   *  'field' => ['type', length]
   * ]
   */

```

- {type} 类型
  - Table::TYPE_INT 默认为4个字节，可以设置1，2，4，8一共4种长度
  - Table::TYPE_STRING设置后，设置的字符串不能超过此长度
  - Table::TYPE_FLOAT会默认占用8个字节的内存

---

## 使用例子

```php

use Swoft\Memory\Table;

$struct = [
        'rule'       => [Table::TYPE_STRING, 100],
        'taskClass'  => [Table::TYPE_STRING, 255],
        'taskMethod' => [Table::TYPE_STRING, 255],
        'add_time'   => [Table::TYPE_STRING, 11],
];

$swoftTable = new Table('shareTable', 1024, $struct)

```

其他基本的api和swoole-table一致(具体也可参见swoole文档)

- set(string $key, array $array) : 设置内容
  - $key，数据的key，相同的$key对应同一行数据，如果set同一个key，会覆盖上一次的数据
  - $value，必须是一个数组，必须与字段定义的$name完全相同

```php
$table->set('swoft', array('host' => 'www.host.com', 'year' => 2017, 'star' => 9999));
```

> table->set() 可以设置全部字段的值，也可以只修改部分字段
> table->set() 未设置前，该行数据的所有字段均为空
> set/get/del 是自带行锁，所以不需要调用lock加锁
> 如果传入字符串长度超过了列定义时设定的最大尺寸，底层会自动截断

- get(string $key, $field = null)：获取一行数据
  - 成功返回结果数组
  - 当指定了$field时仅返回该字段的值，而不是整个记录
  
```php
$table->get('swoft');
```
> 如果$key不存在，将返回false

- incr(string $key, string $column, mixed $incrby = 1) ：原子自增操作
  - $key 指定数据的key，如果$key对应的行不存在，默认列的值为0
  - $column 指定列名，仅支持浮点型和整型字段
  - $incrby 增量，默认为1。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型

```php
$table->set('swoft', 'star');
```

> 失败返回false，成功返回最终的结果数值

- decr(string $key, string $column, mixed $incrby = 1) ：原子自减操作
  - $key 指定数据的key，如果$key对应的行不存在，默认列的值为0
  - $column 指定列名，仅支持浮点型和整型字段
  - $decrby 减量，默认为1。如果列为整形，$decrby必须为int型，如果列为浮点型，$decrby必须为float类型

```php
$table->set('swoft', 'star');
```

> 失败返回false，成功返回最终的结果数值

- exist(string $key) ：检查table中是否存在某一个key

```php
$table->exist('swoft');
```

> 存在返回true，不存在返回false

- del(string $key) ：删除数据

```php
$table->del('swoft');
```

> 成功删除返回true，$key对应的数据不存在，将返回false

- getTable() ： 返回swoole-table对象





