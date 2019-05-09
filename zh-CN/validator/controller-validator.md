# 控制器中使用

如果想在控制器中使用验证器很简单，只需要是一个注解 `@Validate` 就行

- 一个 `action` 可以定义多个 `@Validate` 使用多个验证器
- 多个验证器按照配置顺序验证

如下定义一个 `action`, 同时使用默认验证器和自定义验证器

```php
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;
use App\Validator\TestValidator;

/**
 * Class TestController
 *
 * @since 2.0
 *
 * @Controller(prefix="test")
 */
class TestController
{
    /**
     * @RequestMapping(route="validator")
     *
     * @Validate(validator=TestValidator::class)
     * @Validate(validator="userValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function validator(Request $request): array
    {
        $data = $request->getParsedBody();

        return $data;
    }
}
```

> 注意 `$request->getParsedBody()` 获取的请求数据，是已经通过验证器修改的数据。验证器可以支持表单、请求 body 数据验证，但是 body 验证需要定义对应的数据解析器，框架默认提供 JSON/XML 类型数据解析器，详细介绍，请参考 Http Server 章节。