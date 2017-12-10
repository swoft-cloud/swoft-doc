# HTTP验证器

参数验证器可以PATH(路径参数)、GET和POST三种参数，都是通过注解实现。已经实现常见数据类型参数验证，整数、正整数、浮点数、字符串类型以及枚举类型。

## 常用注解

### @Strings
    
- @Strings字符串类型验证器
- 实例@Strings(from=ValidatorFrom::GET, name="name", min=3, max=10, default="boy")
- from参数定义验证数据类型，默认POST
- name定义验证的名称
- min定义字符串最小长度
- max定义字符串最大长度
- default定义默认值(不存在验证的参数时有效，PATH参数不支持定义默认值)

### @Number

- @Number字符串类型验证器
- 实例@Number(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7)
- from参数定义验证数据类型，默认POST
- name定义验证的名称
- min定义最小值
- max定义最大值
- default定义默认值(不存在验证的参数时有效，PATH参数不支持定义默认值)

### @Integer

- @Integer字符串类型验证器
- 实例@Integer(from=ValidatorFrom::PATH, name="id", min=5, max=10)
- from参数定义验证数据类型，默认POST
- name定义验证的名称
- min定义最小值
- max定义最大值
- default定义默认值(不存在验证的参数时有效，PATH参数不支持定义默认值)

### @Floats

- @Floats字符串类型验证器
- 实例@Floats(from=ValidatorFrom::POST, name="id", min=5.1, max=5.9, default=5.6)
- from参数定义验证数据类型，默认POST
- name定义验证的名称
- min定义最小值
- max定义最大值
- default定义默认值(不存在验证的参数时有效，PATH参数不支持定义默认值)

### @Enum

- @Enum字符串类型验证器
- 实例@Enum(from=ValidatorFrom::POST, name="name", values={1,"a",3}, default=1)
- from参数定义验证数据类型，默认POST
- values定义一个默认枚举数组
- default定义默认值(不存在验证的参数时有效，PATH参数不支持定义默认值)

## 使用实例

```php
/**
 * validator
 *
 * @Controller("validator")
 * @uses      ValidatorController
 * @version   2017年12月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorController
{
    /**
     * @RequestMapping("string/{name}")
     *
     * @Strings(from=ValidatorFrom::GET, name="name", min=3, max=10, default="boy")
     * @Strings(from=ValidatorFrom::POST, name="name", min=3, max=10, default="girl")
     * @Strings(from=ValidatorFrom::PATH, name="name", min=3, max=10)
     *
     * @param string  $name
     * @param Request $request
     *
     * @return array
     */
    public function string(Request $request, string $name)
    {
        $getName  = $request->query('name');
        $postName = $request->post('name');

        return [$getName, $postName, $name];
    }

    /**
     * @RequestMapping("number/{id}")
     *
     * @Number(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7)
     * @Number(from=ValidatorFrom::POST, name="id", min=5, max=10, default=8)
     * @Number(from=ValidatorFrom::PATH, name="id", min=5, max=10)
     *
     * @param \Swoft\Web\Request $request
     * @param int                $id
     *
     * @return array
     */
    public function number(Request $request, int $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }

    /**
     * @RequestMapping("integer/{id}")
     *
     * @Integer(from=ValidatorFrom::GET, name="id", min=5, max=10, default=7)
     * @Integer(from=ValidatorFrom::POST, name="id", min=5, max=10, default=8)
     * @Integer(from=ValidatorFrom::PATH, name="id", min=5, max=10)
     *
     * @param \Swoft\Web\Request $request
     * @param int                $id
     *
     * @return array
     */
    public function integer(Request $request, int $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }

    /**
     * @RequestMapping("float/{id}")
     *
     * @Floats(from=ValidatorFrom::GET, name="id", min=5.1, max=5.9, default=5.6)
     * @Floats(from=ValidatorFrom::POST, name="id", min=5.1, max=5.9, default=5.6)
     * @Floats(from=ValidatorFrom::PATH, name="id", min=5.1, max=5.9)
     *
     * @param \Swoft\Web\Request $request
     * @param float              $id
     *
     * @return array
     */
    public function float(Request $request, float $id)
    {
        $get  = $request->query('id');
        $post = $request->post('id');

        return [$get, $post, $id];
    }


    /**
     * @RequestMapping("enum/{name}")
     *
     * @Enum(from=ValidatorFrom::GET, name="name", values={1,"a",3}, default=1)
     * @Enum(from=ValidatorFrom::POST, name="name", values={1,"a",3}, default=1)
     * @Enum(from=ValidatorFrom::PATH, name="name", values={1,"a",3}, default=1)
     *
     * @param string  $name
     * @param Request $request
     *
     * @return array
     */
    public function estring(Request $request, $name)
    {
        $getName  = $request->query('name');
        $postName = $request->post('name');

        return [$getName, $postName, $name];
    }

}
```