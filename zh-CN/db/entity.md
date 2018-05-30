# 实体定义

无论是高级查询还是基础查询，都会需要一个表实体。一个表字段和一个类属性是一一映射，对类的操作相当于对表的操作，该类称为一个实体

## 注解标签

### @Entity   

标记一个类是一个实体，无需多余参数

参数：

- `instance` 定义实体对应实例，默认 `default` 实例 _对，就是前面配置上的那个`default`实例:)_

> 若需使用基础查询，必须继承Model

### @Table

- name 定义该实体映射的数据库表名

### @Column 

参数：

- name 定义类属性映射的表字段，没该注解标记的属性，不映射
- type 定义字段数据更新时验证类型，暂时提供常见的数据类型延迟，后续会更多

说明：

- 若定义type，可定义其它验证条件
- 所有字段属性，必须要有getter和setter方法

> 类属性默认值即是表字段默认值

### @Id  

该注解标明当前类属性对应了数据库表中的主键，**必须**有这个注解标记

## 示例

```php
/**
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
     * @var null|int
     */
    private $id;

    /**
     * 名称
     *
     * @Column(name="name", type=Types::STRING, length=20)
     * @Required()
     * @var null|string
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

    /**
     * 非数据库字段，未定义映射关系
     *
     * @var mixed
     */
    private $otherProperty;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getSex(): int
    {
        return $this->sex;
    }

    /**
     * @param int $sex
     */
    public function setSex(int $sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getOtherProperty()
    {
        return $this->otherProperty;
    }

    /**
     * @param mixed $otherProperty
     */
    public function setOtherProperty($otherProperty)
    {
        $this->otherProperty = $otherProperty;
    }
}
```
