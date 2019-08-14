# ArrayHelper

为了方便开发swoft官方提供了数组的相关操作，大大提升开发效率。

以下示例代码可以直接运行

## toArray

**功能**

    将对象或对象数组转换为数组。

**概要**

```php
public static function toArray($object, $properties = [], $recursive = true): array
```

**描述**

    参数说明
        
        $object:类型可以是对象、数组、字符串
        
        $properties:从对象类名称到需要放入结果数组的属性的映射。
        
        $recursive:是否以递归方式将对象属性转换为数组（默认是）。
        
**返回值**

    数组对象的数组表示。

**示例**

```php
$objSub = new \stdClass();
$objSub->version = '2.0.6';
$objSub->url = 'https://www.swoft.org';

$obj = new \stdClass();
$obj->name = 'swoft framework 2.x';
$obj->desc = $objSub;

print_r(ArrayHelper::toArray($obj));
/*
Array
(
    [name] => swoft framework 2.x
    [desc] => Array
        (
            [version] => 2.0.6
            [url] => https://www.swoft.org
        )

)
*/


print_r(ArrayHelper::toArray($obj, [get_class($obj) => ['name']]));
/*
Array
(
    [name] => swoft framework 2.x
)
*/


print_r(ArrayHelper::toArray($obj,[],false));
/*
Array
(
    [name] => swoft framework 2.x
    [desc] => stdClass Object
        (
            [version] => 2.0.6
            [url] => https://www.swoft.org
        )

)
*/
```    

## merge

**功能**

    将两个或多个数组合并为一个递归。如果每个数组都有一个具有相同字符串键值的元素，后者将覆盖前者（与array_merge_recursive不同）。
    
    如果两个数组都具有数组类型的元素且具有相同的键，则将进行递归合并。对于整数键控元素，后一个数组中的元素将附加到前一个数组。

**概要**

```php
public static function merge($a, $b): array
```

**描述**

    参数说明
        
        $a:要合并的数组
        
        $b:要合并的数组
        
**返回值**

    数组合并的数组（原始数组不会更改。）

**示例**

```php
$a = ['s'=>1,'w'=>'o','f'=>[9501,9502],'t'=>'swoft',666];
$b = ['t'=>'swoft 2.x',2,3];
print_r(ArrayHelper::merge($a,$b));

/*
Array
(
    [s] => 1
    [w] => o
    [f] => Array
        (
            [0] => 9501
            [1] => 9502
        )

    [t] => swoft 2.x
    [0] => 666
    [1] => 2
    [2] => 3
)
*/
```

## getValue

**功能**

    使用给定的键或属性名称检索数组元素或对象属性的值。
    
    如果数组或对象中不存在该键，则将返回默认值。

**概要**

```php
public static function getValue($array, $key, $default = null)
```

**描述**

    参数说明
        
        $array:数组|对象；要从中提取之的数组或对象
        
        $key:字符串|闭包函数|数组；数组的键名，一个数组的键或者对象的属性名，或一个返回值的匿名函数。这个匿名函数的签名应该是这样的function($array,$defaultValue)
        
        $default:如果指定的数组键不存在，则返回默认值。从对象获取值时不使用。
        
**返回值**

    找到的元素的值，否则为默认值

**示例**

```php
$a = ['s'=>123,'w'=>456];

print_r(ArrayHelper::getValue($a,'s'));//123
print_r(ArrayHelper::getValue($a,'o'));//null
print_r(ArrayHelper::getValue($a,'o','789'));//789


$obj = new \stdClass();
$obj->name='swoft framework';
$obj->version= '2.x';
$obj->desc = ['type'=>'coroutine','fpm'=>'n'];

print_r(ArrayHelper::getValue($obj,function ($obj,$defaultValue){
    return $obj->name.' '.$obj->version;//swoft framework 2.x
}));
print_r(ArrayHelper::getValue($obj,'name'));//swoft framework
print_r(ArrayHelper::getValue($obj,'desc.fpm'));//n
```

## remove

**功能**

    从数组中删除项并返回值。如果数组中不存在该键，则将返回默认值

**概要**

```php
public static function remove(&$array, $key, $default = null)
```

**描述**

    参数说明
        
        $array:从中提取值的数组
        
        $key:数组元素的键名
        
        $default:如果指定的数组键不存在，这个默认值将被返回
        
**返回值**

    找到的元素的值，否则为默认值

**示例**

```php
$a = ['s'=>1,'w'=>2];
ArrayHelper::remove($a,'s');

print_r($a);//['w'=>2]


$b = ['s'=>3,'w'=>4];
ArrayHelper::remove($b,'o');

print_r($b);//['s'=>3,'w'=>4]


$c = ['s'=>5,'w'=>6];
$ret = ArrayHelper::remove($c,'o',[7,8]);

print_r($ret);//[7,8]
```

## except

**功能**

    获取除指定数组键之外的所有给定数组。

**概要**

```php
public static function except($array, $keys): array
```

**描述**

    参数说明
        
        $array:数组
        
        $keys:数组|字符串
        
**返回值**

    void

**示例**

```php
$arr = ['a' => 1, 'b' => 2, 'c' => ['d' => 1, 'e' => 2]];
$ret = ArrayHelper::except($arr, ['a']);

print_r($ret);//['b' => 2, 'c' => ['d' => 1, 'e' => 2]];


$ret2 = ArrayHelper::except($arr, ['a', 'b', 'c.d']);

print_r($ret2);//['c' => ['e' => 2]]
```

## forget

**功能**

    使用“点”表示法从给定数组中删除一个或多个数组项。

**概要**

```php
public static function forget(&$array, $keys): void
```

**描述**

    参数说明
        
        $array:数组
        
        $keys:数组|字符串
        
**返回值**

    void

**示例**

```php
$arr = ['a' => 1, 'b' => 2, 'c' => ['d' => 1, 'e' => 2]];
ArrayHelper::forget($arr, ['a']);
print_r($arr);//['b' => 2, 'c' => ['d' => 1, 'e' => 2]]

$arr2 = ['a' => 1, 'b' => 2, 'c' => ['d' => 1, 'e' => 2]];
ArrayHelper::forget($arr2, ['a', 'b', 'c.d']);
print_r($arr2);//['c' => ['e' => 2]]
```

## pull

**功能**

    从数组中获取值，然后将其删除。

**概要**

```php
public static function pull(&$array, $key, $default = null)
```

**描述**

    参数说明
        
        $array:数组
        
        $key:字符串
        
        $default:
        
**返回值**

    mixed

**示例**

```php
$arr = ['a' => 1, 'b' => 2, 'c' => ['d' => 1, 'e' => 2]];
$rs = ArrayHelper::pull($arr, 'b');

print_r($rs);//2
print_r($arr);//['a' => 1, 'c' => ['d' => 1, 'e' => 2]]


$rs2 = ArrayHelper::pull($arr, 'g', 99);

print_r($rs2);//99
```

## index

**功能**

    根据指定的键索引 和/或 对数组进行分组。输入应该是多维数组或对象数组。
    
    $key可以是子数组的键名，对象的属性名，也可以是必须返回将用作键的值的匿名函数。
    
    $groups是一个键数组，用于根据指定的键将输入数组分组到一个或多个子数组中。
    
    如果$key被指定为null，或者除了未指定$groups之外，对应于该键的元素的值为null，则该元素被丢弃。

**概要**

```php
public static function index($array, $key, $groups = []): array
```

**描述**

    参数说明
        
        $array:array；需要索引或分组的数组
        
        $key:string|Closure|null；列名或匿名函数，其结果将用于索引数组
        
        $groups:string|string[]|Closure[]|null；键数组，用于通过一个或多个键对输入数组进行分组。如果$key属性或其特定元素的值为null并且未定义$groups，则将丢弃该数组元素。否则，如果指定了$groups，则数组元素将添加到结果数组中而不包含任何键。
        
**返回值**

    对索引 和/或 分组数组进行排列

**示例**

```php
$array = [
            ['id' => '123', 'Data' => 'abc', 'device' => 'laptop'],
            ['id' => '345', 'Data' => 'def', 'device' => 'tablet'],
            ['id' => '345', 'Data' => 'hgi', 'device' => 'smartphone'],
        ];
$result = ArrayHelper::index($array, 'id');

print_r($result);
/*
Array
(
    [123] => Array
        (
            [id] => 123
            [Data] => abc
            [device] => laptop
        )

    [345] => Array
        (
            [id] => 345
            [Data] => hgi
            [device] => smartphone
        )

)
*/


$result = ArrayHelper::index($array, function ($element) {
    return $element['id'];
});

print_r($result);
/*
Array
(
    [123] => Array
        (
            [id] => 123
            [Data] => abc
            [device] => laptop
        )

    [345] => Array
        (
            [id] => 345
            [Data] => hgi
            [device] => smartphone
        )

)
*/


$result = ArrayHelper::index($array, null, 'id');

print_r($result);
/*
Array
(
    [123] => Array
        (
            [0] => Array
                (
                    [id] => 123
                    [Data] => abc
                    [device] => laptop
                )

        )

    [345] => Array
        (
            [0] => Array
                (
                    [id] => 345
                    [Data] => def
                    [device] => tablet
                )

            [1] => Array
                (
                    [id] => 345
                    [Data] => hgi
                    [device] => smartphone
                )

        )

)

*/

$result = ArrayHelper::index($array, 'Data', [function ($element) {
    return $element['id'];
}, 'device']);

print_r($result);
/*
Array
(
    [123] => Array
        (
            [laptop] => Array
                (
                    [abc] => Array
                        (
                            [id] => 123
                            [Data] => abc
                            [device] => laptop
                        )

                )

        )

    [345] => Array
        (
            [tablet] => Array
                (
                    [def] => Array
                        (
                            [id] => 345
                            [Data] => def
                            [device] => tablet
                        )

                )

            [smartphone] => Array
                (
                    [hgi] => Array
                        (
                            [id] => 345
                            [Data] => hgi
                            [device] => smartphone
                        )

                )

        )

)
*/
```

## getColumn

**功能**

    返回数组中指定列的值。输入数组应为多维或对象数组。
    
**概要**

```php
public static function getColumn($array, $name, $keepKeys = true): array
```

**描述**

    参数说明
        
        $array:array；
        
        $name:string|Closure；
        
        $keepKeys:boolean；是否维护数组键。如果为false，则生成的数组将使用整数重新编制索引。
        
**返回值**

    数组（列值列表）

**示例**

```php
$array = [
  ['id' => '123', 'Data' => 'abc'],
  ['id' => '345', 'Data' => 'def'],
];
$result = ArrayHelper::getColumn($array, 'id');//['123', '345']

$result2 = ArrayHelper::getColumn($array, function ($element) {
      return $element['id'];
});//['123','345']
```

## map

**功能**

      从多维数组或对象数组构建映射（键值对）。  
      
      $from和$to参数指定用于设置map的键名或属性名。
      
      可选地，可以根据分组字段$group进一步对map进行分组。

**概要**

```php
public static function map($array, $from, $to, $group = null): array
```

**描述**

    参数说明
        
        $array:array
        
        $from:string|Closure
        
        $to:string|Closure
        
        $group:string|Closure
        
**返回值**

    array

**示例**

```php
$array = [
  ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
  ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
  ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
];

$result = ArrayHelper::map($array,'id','name');//['123'=>'aaa','124'=>'bbb','345'=>'ccc']

$result2 = ArrayHelper::map($array,'id','name','class');//['x'=>['123'=>'aaa','124'=>'bbb'],'y'=>['345'=>'ccc']]
```

## keyExists

**功能**

    检查给定数组是否包含指定的键。
    
    此方法通过支持不区分大小写的键比较来增强array_key_exists()函数。

**概要**

```php
public static function keyExists($key, $array, $caseSensitive = true): ?bool
```

**描述**

    参数说明
        
        $key:string；检查的键
        
        $array:array；带有要检查的键的数组
        
        $caseSensitive:boolean；键比较是否应区分大小写
        
**返回值**

    boolean（数组是否包含指定的键）

**示例**

```php
$arr = ['id' => 1, 'data' => 'a'];
$rs = ArrayHelper::keyExists('id', $arr);//true

$rs2 = ArrayHelper::keyExists('name', $arr);//false
```

## multisort













