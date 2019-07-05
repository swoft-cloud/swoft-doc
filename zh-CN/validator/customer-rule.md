# 自定义验证器规则

之前我们介绍过了如何使用自定义验证器，但是有些时候我们想要扩展我们的验证器规则，而非重新自定义一个验证器，对于这种场景的应用，我们也能够比较方便的添加我们自己的验证器规则，请阅读下方详细步骤

> Available: `>= v2.0.3`

## 声明验证器

我们在  [声明验证器](../validator/statement.md)  这一节中声明的验证器基础上，添加一个 `password` 字段，使用一个我们自定义的验证规则来验证该字段。

```php
    /**
     * @AlphaDash(message="Passwords can only be alphabet, numbers, dashes, underscores")
     *
     * @var string
     */
    protected $password;
```
如上我们就添加好了一个 `password` 字段，并且使用了一个 `@AlphaDash()` 的验证规则，该规则就是我们接下来要自定义的规则，它的功能是校验该字段的格式，使其只能是 `字母`,`数字`,`-`,`_`。

思考下由于我们的验证规则是以注解的方式工作的，所以定义验证规则，其实也就是相当于定义一个我们自己的自定义注解命令，这一点清楚了之后我们继续。

## 声明规则注解

> **注意** 我们强烈建议你按照  [应用结构](../quick-start/project-skeleton.md) 中的建议，将自定义的注解定义到 `App/Annotation` 路径中。

### 声明注解命令

```php
<?php declare(strict_types=1);

namespace App\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * Class AlphaDash
 *
 * @since 2.0
 *
 * @Annotation
 * @Attributes({
 *     @Attribute("message",type="string")
 * })
 */
class AlphaDash 
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * StringType constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->message = $values['value'];
        }
        if (isset($values['message'])) {
            $this->message = $values['message'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
```
#### @Annotation

声明这个类是一个注解命令

#### @Attributes

声明注解参数集合

#### @Attribute

声明注解具体的参数

* name 参数的名字
* type 参数值的类型

#### 类属性方法说明

`$message` 就是我们使用该注解时传入的提示。例如 `@IsString(message="该字段必须是字符串")`
`$name` 就是字段的名字，为空的话则默认就是属性名 例如 `@IsString(name="user_name")`
`$value` 则是我们需要传递给验证规则的一些数据，若无需传参则可以不用定义。例如 `@Enum(values=[1,2,3])`
`getMessage()`,`getName()` 方法必须存在。用来获取`$message` 和 `$name` 

### 声明注解解析

至此，我们定义好了注解命令，但是注解命令要想能够执行，则还需要定义一个注解命令的解析器，下方就是一个注解解析器，需要继承
`Swoft\Annotation\Annotation\Parser\Parser` 类。

```php
<?php declare(strict_types=1);

namespace App\Annotation\Parser;

use ReflectionException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use App\Annotation\Mapping\AlphaDash;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\ValidatorRegister;

/**
 * Class AlphaDashParser
 *
 * @AnnotationParser(annotation=AlphaDash::class)
 */
class AlphaDashParser extends Parser
{
    /**
     * @param int $type
     * @param object $annotationObject
     *
     * @return array
     * @throws ReflectionException
     * @throws ValidatorException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_PROPERTY) {
            return [];
        }
        //向验证器注册一个验证规则
        ValidatorRegister::registerValidatorItem($this->className, $this->propertyName, $annotationObject);
        return [];
    }
}
```

#### @AnnotationParser

声明要解析的注解命令

#### parse()
由于我们继承了 `Swoft\Annotation\Annotation\Parser\Parser` , 而它有声明了一个 `Swoft\Annotation\Annotation\Parser\ParserInterface` 接口,而这个方法正是 `ParserInterface` 这个接口所定义的一个必须由我们来实现的一个接口。
其实这里就是我们要处理的业务逻辑了，Swoft 解析到一个注解命令后，就会执行这个注解所对应的解析器中的 `parse()` 这个方法

### 声明一个验证规则

经过之前的步骤我们已经定义好了验证规则的注解以及它的解析器，但是我们还没有定义我们的具体的验证规则，所以接下来，我们将声明我们具体的验证规则，
其实很简单，我们只需要实现一个 `Swoft\Validator\Contract\RuleInterface` 接口就可以了。

```php
<?php declare(strict_types=1);

namespace App\Validator\Rule;

use App\Annotation\Mapping\AlphaDash;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class AlphaDashRule
 *
 * @Bean(AlphaDash::class)
 */
class AlphaDashRule implements RuleInterface
{
    /**
     * @param array $data
     * @param string $propertyName
     * @param object $item
     * @param null $default
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, string $propertyName, $item, $default = null): array
    {
        $message = $item->getMessage();
        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }
        $rule = '/^[A-Za-z0-9\-\_]+$/';
        if (preg_match($rule, $data[$propertyName])) {
            return [$data];
        }
        $message = (empty($message)) ? sprintf('%s must be a email', $propertyName) : $message;
        throw new ValidatorException($message);
    }
}
```

#### @Bean

由于验证器内部是通过 `Bean` 容器来获得到我们的验证规则的，代码如下 
```php
    $rule = BeanFactory::getBean($itemClass);//这里通过容器拿到了我们的验证规则
    $data = $rule->validate($data, $propName, $item, $default);
``` 
所以这里我们就要使用 `@Bean` 来注册我们的验证规则，名字就是和我们的注解命令相同。

#### validate()

这是 `RuleInterface` 接口中规定要实现的方法，到了这里其实就是写我们具体的验证规则了。

 * `array $data` 待验证的所有数据
   
 * `string $propertyName` 需要验证的字段名
 
 * `$item` 注解类的对象
 
 * `$default` 字段的默认值
 
 至此我们已经定义好了一个验证器规则。

