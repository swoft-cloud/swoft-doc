# 声明验证器

一个验证器由多个验证条件组合，建议验证器按数据库表进行组合，这样可以充分的重复利用验证器里面的组合条件。

## 验证器

如下定义一个 TestValidator 验证器，由多个验证项（验证器条件）组成。

```php
use Swoft\Validator\Annotation\Mapping\Email;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TestValidator
 *
 * @since 2.0
 *
 * @Validator()
 */
class TestValidator
{
    /**
     * @IsString()
     *
     * @var string
     */
    protected $name = 'defualtName';

    /**
     * @IsInt(message="类型必须传递且整数")
     *
     * @var int
     */
    protected $type;

    /**
     * @IsString(message="邮箱必须传递且字符串")
     * @Email(message="email 不是邮箱格式")
     *
     * @var string
     */
    protected $email;

    /**
     * @IsInt()
     *
     * @var int
     */
    protected $start = 0;

    /**
     * @IsInt()
     *
     * @var int
     */
    protected $end ;
}
```

### @Validator

声明一个验证器

- name 定义验证器的名称，方便引用，如果不定义默认就是类名全路径。


## 验证项

验证项是组成验证器的唯一条件，标记有类型注解的属性就是一个验证项，一个验证器可以有多个验证项。

- 属性的默认值就是参数的默认值，如果属性没有定义默认值，代表参数没有定义默认值且必须传递。
- 一个属性必须定义一个类型注解，否则不是一个验证项且对参数验证无效。
- 一个属性可以多个条件注解，按照定义顺序验证数据。
- 默认属性名称就是需要验证的参数名称，也可以通过类型注解的 `name` 参数映射需要验证的字段名称


### 类型注解

#### @IsInt

定义参数必须是整数

- name 映射需要验证的字段名称，默认属性名称
- message 验证失败(不是整数或参数没有传递)时的错误提示，默认使用框架内置的。 

#### @IsString

定义参数必须是字符串

- name 映射需要验证的字段名称，默认属性名称
- message 验证失败(不是字符串或参数没有传递)时的错误提示，默认使用框架内置的。 

#### @IsArray

定义参数必须是数组

- name 映射需要验证的字段名称，默认属性名称
- message 验证失败(不是数组或参数没有传递)时的错误提示，默认使用框架内置的。 

#### @IsBool

定义参数必须是 bool 类型，注意字符串 `true` `false` ，会验证成 bool 类型

- name 映射需要验证的字段名称，默认属性名称
- message 验证失败(不是 bool 或参数没有传递)时的错误提示，默认使用框架内置的。 

#### @IsFloat

定义参数必须是浮点数

- name 映射需要验证的字段名称，默认属性名称
- message 验证失败(不是浮点数或参数没有传递)时的错误提示，默认使用框架内置的。 

### 条件注解


#### @Enum

#### @Ip

#### @Length

#### @Max

#### @Min

#### @Mobile

#### @NotEmpty

#### @Pattern

#### @Range