# 定义实体

一个表和一个类是一一映射的，对类的操作相当于对表的操作，该类称为一个实体。实体定义规则如下：

1. 必须使用@Entity注解标明这是一个实体
2. 必须使用@Table命令指定映射的表名
3. 必须使用@column指定表映射字段，没有定义的属性，不映射。
4. 其中一个属性必须使用@Id注解标明主键
5. 所有属性必须有getter和setter方法
6. 属性默认值即是表字段默认值。
7. 若需使用基础查询，必须继承Model类

```php
/**
 * 用户实体
 *
 * @Entity()
 * @Table(name="user")
 */
class User extends Model
{
    /**
     * 主键ID
     *
     * @Id()
     * @Column(name="id", type=Types::INT)
     * @var int
     */
    private $id;

    /**
     * 名称
     *
     * @Column(name="name", type=Types::STRING, length=20)
     * @Required()
     * @var string
     */
    private $name;


    /**
     * 年龄
     *
     * @Column(name="age", type=Types::INT)
     * @var int
     */
    private $age = 0;

    /**
     * 性别
     *
     * @Column(name="sex", type="int")
     * @Enum(value={1,0})
     * @var int
     */
    private $sex = 0;


    /**
     * 描述
     *
     * @Column(name="description", type="string")
     * @var string
     */
    private $desc = "";


    // getter and setter methods
    // ...
}
```

## 注解解析

目前只实现了几个注解，若需其它验证注解比如邮箱、URI、手机号码等，可自行实现。

### @Column()

此注解用于定义类属性和表字段的关系，如果没有使用，默认属性的名称对应表字段名称，定义的字段类型对应数据库字段类型。注解有三个参数可以使用

1. name 定义映射的数据库字段名称
2. type 定义映射的数据库字段类型
3. length 定义字符串最大长度

### @Enum()

此注解是枚举类型注解，定义该字段只能是枚举数组里面的其中一个值，使用如上demo

## @Id()

数据表映射主键ID

## @Required()

属性字段必须传值



