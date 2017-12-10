# service验证器

rpc函数参数验证器是通过注解实现，已经实现常见数据类型参数验证，整数、正整数、浮点数、字符串类型以及枚举类型。service参数验证器无需定义from和default属性，from默认已经是service，默认值通过函数默认值实现。

## 常用注解

### @Strings
    
- @Strings字符串类型验证器
- 实例@Strings(name="name", min=3, max=10)
- name定义验证的名称
- min定义字符串最小长度
- max定义字符串最大长度

### @Number

- @Number字符串类型验证器
- 实例@Number(name="id", min=5, max=10)
- name定义验证的名称
- min定义最小值
- max定义最大值

### @Integer

- @Integer字符串类型验证器
- 实例@Integer(name="id", min=5, max=10)
- name定义验证的名称
- min定义最小值
- max定义最大值

### @Floats

- @Floats字符串类型验证器
- 实例@Floats(name="id", min=5.1, max=5.9)
- name定义验证的名称
- min定义最小值
- max定义最大值

### @Enum

- @Enum字符串类型验证器
- 实例@Enum(name="name", values={1,"a",3})
- values定义一个默认枚举数组

## 使用案例

```php
//**
  * 用户service
  *
  * @Service()
  * @uses      UserService
  * @version   2017年10月15日
  * @author    stelin <phpcrazy@126.com>
  * @copyright Copyright 2010-2016 swoft software
  * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
  */
 class UserService
 {
     // ......
 
 
     /**
      * @Mapping("getUser")
      * @Enum(name="type", values={1,2,3})
      * @Number(name="uid", min=1, max=10)
      * @Strings(name="name", min=2, max=5)
      * @Floats(name="price", min=1.2, max=1.9)
      *
      * @param int    $type
      * @param int    $uid
      * @param string $name
      * @param float  $price
      * @param string $desc  default value
      * @return array
      */
     public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
     {
         return [$type, $uid, $name, $price, $desc];
     }
}
```