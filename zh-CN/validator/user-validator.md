# 自定义验证器

常见的业务默认验证器就能解决，但是有些业务默认验证器是没法验证，此时就需要用户根据自己业务需求，定义满足自己业务的验证器。


## 声明验证器

声明验证器很简单

- 定一个类实现 `Swoft\Validator\Contract\ValidatorInterface` 接口
- 实现 `validate` 方法，里面可以定义自己的业务验证器逻辑
- 使用 `@Validator` 注解标记这是一个验证器，此注解使用和功能和之前介绍完全一样

如下定义了一个验证器，验证 `start` 开始时间不能大于 `end` 结束时间

```php
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Contract\ValidatorInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class UserValidator
 *
 * @since 2.0
 *
 * @Validator(name="userValidator")
 */
class UserValidator implements ValidatorInterface
{
    /**
     * @param array $data
     * @param array $params
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, array $params): array
    {
        $start = $data['start'];
        $end   = $data['end'];

        if ($start > $end) {
            throw new ValidatorException('开始不能大于结束时间');
        }

        return $data;
    }
}
```

验证方法详细介绍

```php
public function validate(array &$data, array $params): array
```
- $data 用户输入参数，通过对于的解析器，已解析成数组
- $params 传递给验证器的参数，后续章节详细介绍

验证成返回验证处理后的数据 , 如果验证失败，抛出 `Swoft\Validator\Exception\ValidatorException` 异常，其它由框架处理。如果验证器里面需要修改参数值，可以直接修改，修改后获取值的地方会得到新的值。
