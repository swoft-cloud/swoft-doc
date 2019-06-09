# 实体生成

实体生成,能快速生成实体文件,`swoft` 中提供了一套简单好用的生成方式.目前官方仅支持 mysql实体生成,其他类型的数据库参照官方例子

## 安装

使用 `composer` 安装 `composer require swoft/devtool` 

## 使用

`php bin/swoft entity:gen`

`php bin/swoft entity:c`

`php bin/swoft entity:create [tableName] [--pool=xx] [--path=xx] [--table_prefix=xx] [--field_prefix=xxx] [--exc=xxx] [--td=xxx] [-y]`

这三个命名是一个意思. 

- **tableName** 指定生成实体的数据表,如果为空会生成匹配到的所有表实体
也可以使用 `--table=tableName` 来指定数据表名,多个表用`,`隔开,
如果设置了数据表前缀,`tableName` 不要带上表前缀.
- **pool** 指定数据库连接池,默认会使用`db.pool`连接池
- **path** 生成实体的路径 支持 `alias` 方式传递路径,默认生成实体路径为`@app/Model/Entity`
比如: `@app/Model/Entity`中的`@app`会解析到项目的绝对路径.
- **table_prefix** 根据数据表前缀生成,简写是 `tp`,该参数不会受db 中的 参数限制`prefix`底层会通过 `like` 进行表前缀匹配
- **field_prefix** 去掉字段名前缀,简写是`fp`,
例如 数据库字段名`t_name`,生成实体的时候 加上`--tp=t_`,生成之后的实体属性名为`name`.
- **exc** 不期望生成的数据表名,使用场景例如你需要生成全部的实体部分表不希望生成实体就可以使用它
多个表用`,`隔开
- **td** 指定生成模板的路径 支持 `alias`方式传递路径,默认使用的模板路径是`'@devtool/devtool/resource/template'`
- **y** 生成文件是否需要确认, `-y` 参数加上就会直接生成文件不会确认提示

## 特别注意

如果 连接池中的 `db`配置中存在`prefix`表前缀配置,生成的实体名会自动去掉表前缀, 其中 `[tableName]`,`--table`,`[--exc=xxx]` 这两个参数查询`数据表`的时候回自动会带上表前缀.

> 请先检查 `db`的`charset` 参数是否配置,不然生成实体中文注释将会乱码.