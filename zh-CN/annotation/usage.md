## 使用
注解使用很简单，和使用一个类一样，首先使用 use 关键字引入类，其次使用注解

```php
use Swoft\Http\Server\Annotation\Mapping\Controller; 

/**
 * 类注释
 *
 * @since 2.0
 * 
 * @Controller("a")
 */
class A
{
}
```

- 第1行引入注解类，像一个普通类一样
- 第8行使用注解，格式 `@类名`，有些类名里面会有参数，比如此注解的 `"a"`

## 原理

为什么 PHP 普通的注释会有能？其实很简单，通过 PHP 反射获取类里面是所有注解(https://www.php.net/manual/zh/book.reflection.php), 其次通过 PHP 组件(https://github.com/doctrine/annotations) 使其实现特殊功能。