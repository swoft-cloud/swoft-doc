# 参数验证器

验证函数参数，保证业务逻辑的完整性。使用验证器只需在方法上面使用，和HTTP验证器一样，唯一不同的是不需要定义from参数

## 实例

```php
    
    // ......
    
    /**
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
    
    //......
```

