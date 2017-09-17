# ORM

ORM用于实现面向对象编程语言里不同类型系统的数据之间的转换，ORM有多种设计模式，swoft采用的是data mapper，业务和实体分开，但是也实现了类似ActiveRecord的操作方式，其实都是同一个实现的。

数据库操作分为两种基础\(类ActiveRecord\)和高级的\(data mapper\)，基础的用于快速开发和常见的查询操作，高级的用于事务和一些复杂的业务查询。

## 查询器

查询器允许使用面向对象的方法组装SQL，无论是基础的还是高级的语法都是一样的。操作语法列表如下：

| 方法 | 功能 |
| :--- | :--- |
| insert | 指定插入表 |
| update | 指向更新的表 |
| delete | 删除语句 |
| select | 查询字段 |
| selects | 查询多个字段 |
| from | 指定删除和查询的表 |
| innerJoin | 内连接 |
| leftJoin | 左连接 |
| rightJoin | 右连接 |
| where | where条件语句 |
| andWhere | where and 条件语句 |
| openWhere | where 里面左括号 |
| closeWhere | where 里面右括号 |
|  |  |
|  |  |
|  |  |
|  |  |
|  |  |



