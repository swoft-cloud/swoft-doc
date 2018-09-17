# HTTP验证器 Validator

参数验证器可以 PATH(路径参数)/GET/POST 三种参数, 都是通过注解实现. 已经实现常见数据类型参数验证, 整数/正整数/浮点数/字符串类型/枚举类型.

代码可以参考 `app/Controller/ValidatorController`

## 常用注解

### @Strings

- `@Strings` 字符串类型验证器
- 实例 `@Strings(from=ValidatorFrom::GET, name="name", min=3, max=10, default="boy", template="字段{name}必须在{min}到{max}之间,您提交的值是{value}")`
- from: 参数定义验证数据类型, 默认 POST
- name: 定义验证的名称
- min: 定义字符串最小长度
- max: 定义字符串最大长度
- default: 定义默认值, PATH参数不支持定义默认值, 参数不存在有效
- template: 自定义模板提示
### @Number

- `@Number` 正整数验证器
- 实例 `@Number(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7, template="字段{name}必须在{min}到{max}之间,您提交的值是{value}")`
- from: 参数定义验证数据类型, 默认POST
- name: 定义验证的名称
- min: 定义最小值
- max: 定义最大值
- default: 定义默认值, PATH参数不支持定义默认值, 参数不存在有效
- template: 自定义模板提示
### @Integer

- `@Integer` 整数验证器
- 实例 `@Integer(from=ValidatorFrom::PATH, name="id", min=5, max=10, template="字段{name}必须在{min}到{max}之间,您提交的值是{value}")`
- from: 参数定义验证数据类型, 默认 POST
- name: 定义验证的名称
- min: 定义最小值
- max: 定义最大值
- default: 定义默认值, PATH参数不支持定义默认值, 参数不存在有效
- template: 自定义模板提示
### @Floats

- `@Floats` 浮点数验证器
- 实例 `@Floats(from=ValidatorFrom::POST, name="id", min=5.1, max=5.9, default=5.6, template="字段{name}必须在{min}到{max}之间,您提交的值是{value}")`
- from: 参数定义验证数据类型, 默认 POST
- name: 定义验证的名称
- min: 定义最小值
- max: 定义最大值
- default: 定义默认值, PATH参数不支持定义默认值, 参数不存在有效
- template: 自定义模板提示
### @Enum

- `@Enum` 枚举验证器
- 实例 `@Enum(from=ValidatorFrom::POST, name="name", values={1,"a",3}, default=1, template="字段{name}必须的,您提交的值是{value}")`
- from: 参数定义验证数据类型, 默认 POST
- values: 定义一个默认枚举数组
- default: 定义默认值, PATH参数不支持定义默认值, 参数不存在有效
- template: 自定义模板提示
