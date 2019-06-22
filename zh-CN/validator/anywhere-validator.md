# 非注解验证器

注解方式引用和使用验证器是有限制，只支持在 Http server/ Rpc server /Websocket server 等特定位置使用，在实际业务开发中，其它地方也会涉及参数的验证。
非注解和注解方式都是引用相同的验证器，一个验证器可以多个位置，多种方式使用，大大的减少了代码的重复成本。

## 

```php
function validate(array $data, string $validatorName, array $fields = [], array $userValidators = []): array
```

全局函数使用，当验证器失败会抛出 `Swoft\Validator\Exception\ValidatorException` 异常

- $data 需要验证的数据，必须是数组 KV 格式
- $validatorName 使用的验证器( `@Validator()` 注解标记的 )
- $fields 需要验证的字段，为空验证器所有字段
- $userValidators 同时使用的自定义验证器，支持两种格式。


所有参赛验证

```php
use Swoft\Validator\Annotation\Mapping\Validator;

$data = [
    'email' => 'swoft@xx'
]

$result = validate($data, Validator::class);
```

指定字段验证

```php
use Swoft\Validator\Annotation\Mapping\Validator;

$data = [
    'email' => 'swoft@xx'
]

$result = validate($data, Validator::class, ['email']);
```

同时使用自定义验证器

```php
use Swoft\Validator\Annotation\Mapping\Validator;

$data = [
    'start'  => 12,
    'end'    => 16,
];


$result = validate($data, Validator::class, [], ['testUserValidtor']);
```

同时使用自定义验证器且传递参数

```php
use Swoft\Validator\Annotation\Mapping\Validator;

$data = [
    'start'  => 12,
    'end'    => 16,
    'params' => [1, 2]
];

$users = [
    'testUserValidtor' => [1, 2]
];

$result = validate($data, Validator::class, [], $users);
```