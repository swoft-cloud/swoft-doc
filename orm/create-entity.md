# 命名创建实体

如果想通过数据表来映射当前的ORM，可以通过命令行的形式来创建实体，前提是你要确保你的数据库配置已经完成并且可连接可读取数据

## 命令说明

通过以下命令可以查看创建实体命令的帮助

```
php bin/swoft entity:create -h
```

详情说明如下：

```
Description:
  Auto create entity by table structure

Usage:
  entity:create -d[|--database] <database>
  entity:create -d[|--database] <database> [table]
  entity:create -d[|--database] <database> -i[|--include] <table>
  entity:create -d[|--database] <database> -i[|--include] <table1,table2>
  entity:create -d[|--database] <database> -i[|--include] <table1,table2> -e[|--exclude] <table3>
  entity:create -d[|--database] <database> -i[|--include] <table1,table2> -e[|--exclude] <table3,table4>

Options:
  -d           数据库
  --database   数据库
  -i           指定特定的数据表，多表之间用逗号分隔
  --include    指定特定的数据表，多表之间用逗号分隔
  -e           排除指定的数据表，多表之间用逗号分隔
  --exclude    排除指定的数据表，多表之间用逗号分隔

Example:
  php bin/swoft entity:create -d test
```
