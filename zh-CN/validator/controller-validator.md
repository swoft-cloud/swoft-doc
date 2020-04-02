# 控制器中使用

如果想在控制器中使用验证器很简单，只需要是一个注解 `@Validate` 就行

- 一个 `action` 可以定义多个 `@Validate` 使用多个验证器
- 多个验证器按照配置顺序验证

如下定义一个 `ValidatorController`, 同时使用默认验证器和自定义验证器以及我们自定义的验证规则。

```php
<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;

/**
 * Class ValidatorController
 *
 * @Controller()
 */
class ValidatorController
{
    /**
     * 验证TestValidator验证器中的所有已定义字段
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator")
     * @param Request $request
     *
     * @return array
     */
    public function validateAll(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 仅验证TestValidator验证器中的 type 字段
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator",fields={"type"})
     * @param Request $request
     *
     * @return array
     */
    public function validateType(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 仅验证TestValidator验证器中的 password 字段 password字段使用的是自定义的验证规则。
     *
     * @RequestMapping()
     * @Validate(validator="TestValidator",fields={"password"})
     * @param Request $request
     *
     * @return array
     */
    public function validatePassword(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * 使用userValidator自定义验证器
     *
     * @RequestMapping()
     * @Validate(validator="userValidator")
     * @param Request $request
     *
     * @return array
     */
    public function validateCustomer(Request $request): array
    {
        return $request->getParsedBody();

    }
}

```

- `$request->getParsedBody()` 所有解析数据
- `$request->parsedBody('key', 'default')`  指定 KEY 解析数据
- `$request->getParsedQuery()` 所有解析的 Query 参数
- `$request->parsedQuery('key', 'default')`  指定 KEY 解析数据(>=2.0.2)

## @Validate

- validator 指定验证器名称
- fields 指定验证器里面验证的字段，这样可以高效的重复使用验证器
- type 默认 `body`，`ValidateType::GET` 验证 GET 请求 query 参数
- params 自定义验证器使用，传递给自定义验证器的参数

> 注意 `$request->getParsedBody()` 获取的请求数据，是已经通过验证器修改的数据。验证器可以支持表单、请求 body 数据验证，但是 body 验证需要定义对应的数据解析器，框架默认提供 JSON/XML 类型数据解析器，详细介绍，请参考 Http Server 章节。

验证 GET 请求 query 参数：

```php
use Swoft\Validator\Annotation\Mapping\ValidateType;

... ...

    /**
     * @RequestMapping()
     * @Validate(validator="TestValidator", type=ValidateType::GET)
     * @param Request $request
     *
     * @return array
     */
    public function validateType(Request $request): array
    {
        return $request->getParsedBody();
    }
```
