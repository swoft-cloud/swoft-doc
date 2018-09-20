# 查询器
查询器，提供可以使用面向对象的方法操作数据库。

## 方法列表

| 方法 | 功能 |
| :--- | :--- |
| insert | 插入数据 |
| batchInsert | 批量插入数据 |
| update | 更新数据 |
| delete | 删除数据 |
| counter | count数据 |
| get | 查询数据 |
| one | 查询一行数据 |
| table | 指定表名及别名 |
| innerJoin | 内连接 |
| leftJoin | 左连接 |
| rightJoin | 右连接 |
| condition | 通过数组结构快速指定条件 |
| where | where 条件语句 |
| andWhere | where and 条件语句 |
| openWhere | where 里面左括号 |
| closeWhere | where 里面右括号 |
| orWhere | where or 条件语句 |
| whereIn | where in语句 |
| whereNotIn | where not in 语句 |
| whereBetween | where between and 语句 |
| whereNotBetween | where not between and语句 |
| having | having语句 |
| andHaving | having and语句 |
| orHaving | having or语句 |
| havingIn | having in语句 |
| havingNotIn | having not in语句 |
| havingBetween | having between and语句 |
| havingNotBetween | havin not between and 语句 |
| openHaving | having括号开始语句 |
| closeHaving | having括号结束语句 |
| groupBy | group by语句 |
| orderBy | order by语句 |
| condition | 条件查询 |
| limit | limit语句 |
| count | count语句 |
| max | max语句 |
| min | min语句 |
| avg | avg语句 |
| sum | sum语句 |
| setParameter | 设置参数 |
| setParameters | 设置多个参数 |
| selectDb | 设置连接的DB |
| selectNode | 选择连接的节点 |
| selectInstance | 选择连接的实例 |
| force | 强制使用 Master 节点 |
| className | 设置数据的实体对象类 |

## 获取最后执行的 SQL
直接通过 `get_last_sql()` 函数从 SQLStack 中获得最后执行的 SQL

## 规则与格式

- 语句中的表名，可以是数据库表名，也可以是表对应的实体类名
- 查询器都是通过getResult\(\)方法获取结果
- 插入操作，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false
- 更新操作，成功返回影响行数，如果失败返回false
- 删除操作，成功返回影响行数，如果失败返回false
- 查询操作，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组
