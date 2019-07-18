# 声明验证器

一个验证器由多个验证条件组合，建议验证器按数据库表进行组合，这样可以充分的重复利用验证器里面的组合条件。

## 验证器

如下定义一个 TestValidator 验证器，由多个验证项（验证器条件）组成。

```php
<?php declare(strict_types=1);

namespace App\Validator;

use App\Annotation\Mapping\AlphaDash;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TestValidator
 *
 * @since 2.0
 *
 * @Validator(name="TestValidator")
 */
class TestValidator
{
    /**
     * @IsString() //类型注解
     * @Email() //条件注解
     * @var string
     */
    protected $name = 'defualtName';

    /**
     * @IsInt(message="type must Integer")
     *
     * @var int
     */
    protected $type;
}
```

### @Validator

声明一个验证器

- name 定义验证器的名称，方便引用，如果不定义默认就是类名全路径。


## 验证项

`验证项`是组成验证器的唯一条件，标记有`类型注解`的属性就是一个`验证项`，一个验证器可以有多个`验证项`。

- `属性`的默认值就是`参数`的默认值，如果`属性`没有定义默认值，代表`参数`没有定义默认值且必须传递。
- 一个`属性`必须定义一个`类型注解`，否则不是一个验证项且对参数验证无效。
- 一个属性可以多个`条件注解`，按照定义顺序验证数据。
- 默认`属性`名称就是需要验证的`参数名称`，也可以通过类型注解的 `name` 参数映射需要验证的字段名称。
- 若验证不通过时，将会抛出 `Swoft\Validator\Exception\ValidatorException` 异常。
 
### 类型注解

> 一个属性必须定义一个类型注解，否则不是一个验证项且对参数验证无效。

> 属性的默认值就是参数的默认值，如果属性没有定义默认值，代表参数没有定义默认值且必须传递。

### @IsArray

验证规则:

验证参数值必须是整数,使用 `is_array()`函数进行校验。

参数说明:
- `name:` 映射需要验证的字段名称，默认属性名称。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@IsArray(name="field", message="error message")`。

### @IsBool

验证规则:

验证参数值必须是 `bool` 类型，注意字符串 `true` `false` ，会验证成 `bool` 类型,其余数据将会使用`is_bool()`函数进行验证。

参数说明:
- `name:` 映射需要验证的字段名称，默认属性名称。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@IsBool(name="field", message="error message")`。

### @IsFloat

验证规则:

验证参数值必须是浮点数,使用`filter_var()`函数进行验证。

参数说明:
- `name:` 映射需要验证的字段名称，默认属性名称。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@IsFloat(name="field", message="error message")`。

### @IsInt

验证规则:

验证参数值必须是整数，使用`filter_var()`函数进行验证。

参数说明:
- `name:` 映射需要验证的字段名称，默认属性名称。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@IsInt(name="field", message="error message")`。

### @IsString

验证规则:

验证参数值必须是字符串，使用`is_string()`函数进行验证。

参数说明:
- `name:` 映射需要验证的字段名称，默认属性名称。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@IsString(name="field", message="error message")`。

### 条件注解

> 一个属性可以多个条件注解，按照定义顺序验证数据。

### @AfterDate

验证规则:

验证参数值必须在某个日期之后，参数支持 `字符串时间戳`、`格式化日期字符串（只支持 Y-m-d H:i:s）`、`整型时间戳`，可在`@IsString` 或 `@IsInt` 类型注解中使用。

参数说明:
- `date:` 要对比的日期值，只能是 `Y-m-d H:i:s` 格式。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@AfterDate(date="2019-01-01 00:00:00", message="error message")`。

### @Alpha

验证规则:

验证参数值必须是 `大写字母` 或 `小写字母`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Alpha(message="error message")`。

### @AlphaDash

验证规则:

验证参数值必须是 `大写字母` 、 `小写字母`、`数字`、`短横 -`、`下划线 _`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@AlphaDash(message="error message")`。

### @AlphaNum

验证规则:

验证参数值必须是 `大写字母` 、 `小写字母`、`数字`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@AlphaNum(message="error message")`。

### @BeforeDate

验证规则:

验证参数值必须在某个日期之前，支持 `字符串时间戳`、`格式化日期字符串（只支持 Y-m-d H:i:s）`、`整型时间戳`，可在`@IsString` 或 `@IsInt` 类型注解中使用。

参数说明:
- `date:` 要对比的日期值，只能是 `Y-m-d H:i:s` 格式。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@BeforeDate(date="2019-01-01 00:00:00", message="error message")`。

### @Chs

验证规则:

验证参数值只能是 `中文`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Chs(message="error message")`。

### @ChsAlpha

验证规则:

验证参数值必须是 `中文`、`大写字母` 、 `小写字母`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@ChsAlpha(message="error message")`。

### @ChsAlphaDash

验证规则:

验证参数值必须是 `中文`、`大写字母` 、 `小写字母`、`数字`、`短横 -`、`下划线 _`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@ChsAlphaDash(message="error message")`。

### @ChsAlphaNum

验证规则:

验证参数值必须是 `中文`、`大写字母` 、 `小写字母`、`数字`。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@ChsAlphaNum(message="error message")`。

### @Confirm

验证规则:

验证参数值必须和另外一个字段参数值相同。

参数说明:
- `name:` 需要确认对比的字段名,在`类型注解`中设置过的 `name` 或者是默认的 `属性名`。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Confirm(name="field", message="error message")`。

### @Different

验证规则:

验证参数值必须和另外一个字段参数值不同。

参数说明:
- `name:` 需要确认对比的字段名,在`类型注解`中设置过的 `name` 或者是默认的 `属性名`。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Different(name="field", message="error message")`。

### @GreaterThan

验证规则:

验证参数值必须比另外一个字段参数值大，只支持 `int` 或 `float`, 字符串会被转化为 float 后进行对比。

参数说明:
- `name:` 需要确认对比的字段名,在`类型注解`中设置过的 `name` 或者是默认的 `属性名`。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@GreaterThan(name="field", message="error message")`。

### @LessThan

验证规则:

验证参数值必须比另外一个字段参数值小，只支持 `int` 或 `float`, 字符串会被转化为 float 后进行对比。

参数说明:
- `name:` 需要确认对比的字段名,在`类型注解`中设置过的 `name` 或者是默认的 `属性名`。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@LessThan(name="field", message="error message")`。

### @Date

验证规则:

验证参数值必须是日期格式，支持 `字符串时间戳`、`格式化日期字符串（只支持 Y-m-d H:i:s）`、`整型时间戳`，可在`@IsString` 或 `@IsInt` 类型注解中使用。

>  注意由于时间戳的特殊性默认为一个整型 大于 `PHP_INT_MIN` , 小于 `PHP_INT_MAX` 常量的数值均为有效时间戳。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Date(message="error message")`。

### @DateRange

验证规则:

验证参数值必须在某个日期范围之内（可以等于临界日期），支持 `字符串时间戳`、`格式化日期字符串（只支持 Y-m-d H:i:s）`、`整型时间戳`，可在`@IsString` 或 `@IsInt` 类型注解中使用。

参数说明:
- `start:` 要对比的开始日期值，只能是 `Y-m-d H:i:s` 格式。
- `end:` 要对比的结束日期值，只能是 `Y-m-d H:i:s` 格式。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@DateRange(start="2019-01-01 00:00:00",end="2019-01-01 00:00:00", message="error message")`。

### @Dns

验证规则:

验证参数值必须是一个具有有效 DNS 记录域名或者ip，使用 `checkdnsrr()` 函数校验。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Dns(message="error message")`。

### @Email

验证规则:

验证参数值格式必须为邮箱

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Email(message="error message")`。

### @Enum

验证规则:

验证参数值必须在枚举数组里面。

参数说明:
- `values:` 枚举数组集合
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Enum(values={1,2,3},message="error message")`。

### @File

验证规则:

验证此参数的值必须是文件，可以是单个文件，也可以是表单数组上传的多个文件。**注意文件上传后文件域的获取需要通过 `Swoft\Http\Message\Request` 对象去获取**。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@File(message="error message")`。

### @FileMediaType

验证规则:
> 使用此条件前必须使用 `@File` 规则为基础。
验证每个上传的文件 mediaType 类型,支持表单数组，批量文件。

参数说明:
- `mediaType:` mediaType类型数组。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@FileMediaType(mediaType={"image/gif","image/png"},message="error message")`。

### @FileSize

验证规则:
> 使用此条件前必须使用 `@File` 规则为基础。
验证每个上传的文件尺寸大小（单位 `byte 字节`）,支持表单数组，批量文件。

参数说明:
- `size:` 文件尺寸大小，单位 `byte`。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@FileSize(size=1024,message="error message")`。

### @FileSuffix

验证规则:

> 使用此条件前必须使用 `@File` 规则为基础。
验证每个上传的文件后缀名,支持表单数组，批量文件。

参数说明:
- `suffix:` 后缀名数组。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@FileSuffix(suffix={"png","jpg"},message="error message")`。

### @Ip

验证规则:

验证参数值必须是个IP类型。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Ip(message="error message")`。

### @Length

验证规则:

验证参数值长度限制。

参数说明:
- `min:` 最小值(包含当前值)。
- `max:` 最大值(包含当前值)。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Length(min=1,max=4,message="error message")`。

### @Low

验证规则:

验证参数值必须是小写字母。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Low(message="error message")`。

### @Max

验证规则:

最大值验证，必须是整数。

参数说明:
- `value` 最大值(包含当前值)
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Max(value=5,message="error message")`。

### @Min

验证规则:

最小值验证

参数说明:
- `value` 最小值(包含当前值)
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Min(value=5,message="error message")`。

### @Mobile

验证规则:

手机号验证。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Mobile(message="error message")`。

### @NotEmpty

验证规则:

参数值不能为空验证。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@NotEmpty(message="error message")`。

### @NotInEnum

验证规则:

验证参数值必须不在枚举数组中。

参数说明:
- `values` 枚举数组集合。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@NotInEnum(values={1,2,3},message="error message")`。

### @NotInRange

验证规则:

验证参数值必须不在范围内

参数说明:
- `min:` 最小值(包含当前值)。
- `max:` 最大值(包含当前值)。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@NotInRange(min=1,max=5,message="error message")`。

### @Pattern

验证规则:

正则表达式验证。

参数说明:
- `regex:` 正则表达式。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Pattern(regex="/^1\d{10}$/", message="error message")`。

### @Range

验证规则:

参数值范围验证。

参数说明:
- `min:` 最小值(包含当前值)。
- `max:` 最大值(包含当前值)。
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Range(min=1,max=5,message="error message")`。

### @Upper

验证规则:

验证参数值必须是大写字母。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Range(message="error message")`。

### @Url

验证规则:

验证参数值必须是有效的URL格式，使用 `filter_var()`函数验证。

参数说明:
- `message:` 验证失败时的错误提示，若不设置则默认使用框架内置的。

使用示例:
`@Url(message="error message")`。

> 暂时官方提供了这些条件验证，如果有其它需求可以根据 [自定义验证器规则](../validator/customer-rule.md) 此章节内容自行添加验证规则。