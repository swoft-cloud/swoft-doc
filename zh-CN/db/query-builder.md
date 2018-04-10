# 查询器
查询器，提供可以使用面向对象的方法操作数据库。

## 方法列表

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
| set | 设置更新值 |
| setParameter | 设置参数 |
| setParameters | 设置多个参数 |
| getSql | 获取执行语句 |

## 规则与格式

- 语句中的表名，可以是数据库表名，也可以是表对应的实体类名
- 查询器都是通过getResult\(\)方法获取结果
- 插入操作，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false
- 更新操作，成功返回影响行数，如果失败返回false
- 删除操作，成功返回影响行数，如果失败返回false
- 查询操作，单条记录成功返回一维数组或一个实体，多条记录返回多维数组或实体数组
