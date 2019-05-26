# 关于注解

## 什么是注解

注解其实就 PHP 代码里面的注释，但是它是有功能含义的。

```php
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * 类注释
 *
 * @since 2.0
 * 
 * @Controller("a")
 */
class A
{
    /**
     * 属性注释
     *
     * @Inject()
     *
     * @var string
     */
    private $pro = '';
    
    /**
     * @RequestMapping()
     * 
     * 方法注释
     */
    public function method(): void
    {

    }
}
```

- `@Controller` 注释，定义一个控制器，后续章节详细介绍。
- `@Inject` 注释，定义一个依赖，后续章节详细介绍。
- `@RequestMapping` 注释，定义一个 action，后续章节详细介绍。

<p class="tip">
    <strong>严重警告⚠️</strong> 注解必须以 <code>/**</code>开始，并以 <code>*/</code> 结束。否则会导致无法解析！
</p>

## 原理

## 规范

PHP 代码里面本身就会有很多注释，开发者写的有功能的注解，如果随意乱写，会导致不好维护。Swoft 框架源码，注解规范如下：

- 类注解，所有类注释后面
- 属性注解，属性描述之后，其它注释之前
- 方法注解，方法描述之后，其它注释之前

> Swoft 框架里面的注解规范仅供参考，如果有更好的方式，欢迎讨论。
