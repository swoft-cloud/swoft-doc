# ArrayHelper

为了方便开发swoft官方提供了数组的基础帮助库，大大提升开发效率。

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
        
        $object:object|array|string；要转换为数组的对象。
        
        $properties:array；从对象类名称到需要放入结果数组的属性的映射。
        
        $recursive:boolean；是否以递归方式将对象属性转换为数组（默认是）。
        
**返回值**

    array（对象的数组表示）

**示例**

```php
$objSub = new \stdClass();
$objSub->version = '2.0.6';
$objSub->url = 'https://www.swoft.org';

$obj = new \stdClass();
$obj->name = 'swoft framework 2.x';
$obj->desc = $objSub;

print_r(ArrayHelper::toArray($obj));//['name'=>'swoft framework 2.x','desc'=>['version'=>'2.0.6','url'=>'https://www.swoft.org']]

print_r(ArrayHelper::toArray($obj, [get_class($obj) => ['name']]));//['name'=>'swoft framework 2.x']

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
        
        $a:array；要合并的数组。
        
        $b:array；要合并的数组。
        
**返回值**

    array（数组合并的数组（原始数组不会更改。））

**示例**

```php
$a = ['s'=>1,'w'=>'o','f'=>[9501,9502],'t'=>'swoft',666];
$b = ['t'=>'swoft 2.x',2,3];
print_r(ArrayHelper::merge($a,$b));//['s'=>1,'w'=>'o','f'=>[0=>9501,1=>9502],'t'=>'swoft 2.x',0=>666,1=>2,2=>3]
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
        
        $array:array|object；要从中提取之的数组或对象。
        
        $key:string|Closure|array；数组的键名，一个数组的键或者对象的属性名，或一个返回值的匿名函数。这个匿名函数的签名应该是这样的function($array,$defaultValue)。
        
        $default:mixed；如果指定的数组键不存在，则返回默认值。从对象获取值时不使用。
        
**返回值**

    mixed（找到的元素的值，否则为默认值）

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
    return $obj->name.' '.$obj->version;
}));//swoft framework 2.x
print_r(ArrayHelper::getValue($obj,'name'));//swoft framework
print_r(ArrayHelper::getValue($obj,'desc.fpm'));//n
```

## remove

**功能**

    从数组中删除项并返回值。如果数组中不存在该键，则将返回默认值。

**概要**

```php
public static function remove(&$array, $key, $default = null)
```

**描述**

    参数说明
        
        $array:array；从中提取值的数组。
        
        $key:string；数组元素的键名。
        
        $default:mixed；如果指定的数组键不存在，这个默认值将被返回。
        
**返回值**

    mixed|null（找到的元素的值，否则为默认值）

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
        
        $array:array；
        
        $keys:array|string；
        
**返回值**

    array

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
        
        $array:array；
        
        $keys:array|string；
        
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
        
        $array:array；
        
        $key:string；
        
        $default:mixed；
        
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
        
        $array:array；需要索引或分组的数组。
        
        $key:string|Closure|null；列名或匿名函数，其结果将用于索引数组。
        
        $groups:string|string[]|Closure[]|null；键数组，用于通过一个或多个键对输入数组进行分组。如果$key属性或其特定元素的值为null并且未定义$groups，则将丢弃该数组元素。否则，如果指定了$groups，则数组元素将添加到结果数组中而不包含任何键。
        
**返回值**

    array（对索引 和/或 分组数组进行排列）

**示例**

```php
$array = [
            ['id' => '123', 'Data' => 'abc', 'device' => 'laptop'],
            ['id' => '345', 'Data' => 'def', 'device' => 'tablet'],
            ['id' => '345', 'Data' => 'hgi', 'device' => 'smartphone'],
        ];
$result = ArrayHelper::index($array, 'id');

print_r($result);
//['123'=>['id'=>'123','Data'=>'abc','device'=>'laptop'],'345'=>['id'=>'345','Data'=>'hgi','device'=>'smartphone']]


$result = ArrayHelper::index($array, function ($element) {
    return $element['id'];
});

print_r($result);
//['123'=>['id'=>'123','Data'=>'abc','device'=>'laptop'],'345'=>['id'=>'345','Data'=>'hgi','device'=>'smartphone']]


$result = ArrayHelper::index($array, null, 'id');

print_r($result);//['123'=>[0=>['id'=>'123','Data'=>'abc','device'=>'laptop']],'345'=>[0=>['id'=>'345','Data'=>'def','device'=>'tablet'],1=>['id'=>'345','Data'=>'hgi','device'=>'smartphone']]]

$result = ArrayHelper::index($array, 'Data', [function ($element) {
    return $element['id'];
}, 'device']);

print_r($result);
//['123'=>['laptop'=>['abc'=>['id'=>'123','Data'=>'abc','device'=>'laptop']]],'345'=>['tablet'=>['def'=>['id'=>'345','Data'=>'def','device'=>'tablet']],'smartphone'=>['hgi'=>['id'=>'345','Data'=>'hgi','device'=>'smartphone']]]]
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

    array（列值列表）

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
        
        $key:string；检查的键。
        
        $array:array；带有要检查的键的数组。
        
        $caseSensitive:boolean；键比较是否应区分大小写。
        
**返回值**

    boolean（数组是否包含指定的键）

**示例**

```php
$arr = ['id' => 1, 'data' => 'a'];
$rs = ArrayHelper::keyExists('id', $arr);//true

$rs2 = ArrayHelper::keyExists('name', $arr);//false
```

## multisort

**功能**

    通过一个或多个键对一组对象或数组进行排序（具有相同的结构）。

**概要**

```php
public static function multisort(&$array, $key, $direction = SORT_ASC, $sortFlag = SORT_REGULAR): void
```

**描述**

    参数说明
        
        $array:array；要排序的数组。调用此方法后，将修改该数组。
        
        $key:string|Closure|array；要排序的key(s)。这指的是子数组元素的键名，对象的属性名，或返回值以进行比较的匿名函数。匿名函数签名应该是：function（$item）。要按多个键排序，在这里提供一组键。
        
        $direction:integer|array；排序方向。它可以是SORT_ASC或SORT_DESC。使用具有不同排序方向的多个键进行排序时，请使用排序方向数组。
        
        $sortFlag:integer|array；PHP排序标志。有效值包括SORT_REGULAR，SORT_NUMERIC，SORT_STRING，SORT_LOCALE_STRING，SORT_NATURAL和SORT_FLAG_CASE。有关详细信息，请参阅[PHP手册]（http://php.net/manual/en/function.sort.php）。使用具有不同排序标志的多个键进行排序时，请使用排序标志数组。
        
**返回值**

    void

**异常**

    如果$direction或$sortFlag参数的元素数与$key的元素数不正确，则返回InvalidArgumentException。

**示例**

```php
$arr = [
    ['id' => 1,'age'=>33],
    ['id' => 5,'age'=>45],
    ['id' => 1,'age'=>41],
];
ArrayHelper::multisort($arr,function ($item){
    return $item['age'];
},SORT_DESC);
        
print_r($arr);//[['id'=>5,'age'=>45],['id'=>1,'age'=>41],['id'=>1,'age'=>33]]
        
        
$arr2 = [
    ['id' => 1,'age'=>33],
    ['id' => 5,'age'=>45],
    ['id' => 1,'age'=>41],
];
ArrayHelper::multisort($arr2, ['id','age'],SORT_DESC);

print_r($arr2);//[['id'=>5,'age'=>45],['id'=>1,'age'=>41],['id'=>1,'age'=>33]]
```

## isAssociative

**功能**

    返回一个值，指示给定数组是否为关联数组。
    
    如果数组的所有键都是字符串，则该数组是关联的。如果$allStrings为false，那么如果一个数组中至少有一个是一个字符串，则该数组将被视为关联数组。
    
    请注意，空数组不会被视为关联数组。

**概要**

```php
public static function isAssociative($array, $allStrings = true): ?bool
```

**描述**

    参数说明
        
        $array:array；正在检查的数组。
        
        $allStrings:boolean；
        
**返回值**

    boolean（数组是否是关联的）

**示例**

```php
$arr = ['id' => 1, 'data' => 'a'];
$rs = ArrayHelper::isAssociative($arr);//true

$arr2 = [1 => 1, 'data' => 'a'];
$rs2 = ArrayHelper::isAssociative($arr2);//false

$rs3 = ArrayHelper::isAssociative($arr2, false);//true
```

## isIndexed

**功能**
    
    返回一个值，指示给定数组是否为索引数组。
    
    如果数组的所有键都是整数，则对其进行索引。如果$consecutive为真，则数组键必须是从0开始的连续序列。
    
    注意，空数组将被认为是索引的。

**概要**

```php
public static function isIndexed($array, $consecutive = false): ?bool
```

**描述**

    参数说明
        
        $array:array；被检查的数组。
        
        $consecutive:boolean；数组键是否必须是连续序列，以便将数组视为索引。
        
**返回值**

    boolean（数组是否是关联的）

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::isIndexed($arr);//true

$arr = ['a', 'b', 5 => 'c'];
$rs = ArrayHelper::isIndexed($arr);//true

$arr = ['a', 'b', 'key' => 'c'];
$rs = ArrayHelper::isIndexed($arr);//false

$arr = ['a', 'b', 5 => 'c'];
$rs = ArrayHelper::isIndexed($arr, true);//false
```

## isIn

**功能**

    检查数组或[[\Traversable]]是否包含元素。
    
    此方法与PHP函数[in_array()]（http://php.net/manual/en/function.in-array.php）相同，但也适用于实现[[\Traversable]]接口的对象。

**概要**

```php
public static function isIn($needle, $haystack, $strict = false): bool
```

**描述**

    参数说明
        
        $needle:mixed；待查找的值。
        
        $haystack:array|Traversable；要搜索的值集。
        
        $strict:boolean；是否启用strict（`===`）比较。
        
**返回值**

    boolean（如果$needle在$haystack中找到，则为true，否则为false。）

**异常**

    如果$haystack既不可遍历也不是数组，则返回InvalidArgumentException。

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::isIn('b', $arr);//true

$rs2 = ArrayHelper::isIn('d', $arr);//false
```

## isTraversable

**功能**

    检查变量是数组还是[[\Traversable]]。
    
    此方法与PHP函数[is_array()]（http://php.net/manual/en/function.is-array.php）相同，但另外适用于实现[[\Traversable]]接口的对象。
    
**概要**

```php
public static function isTraversable($var): bool
```

**描述**

    参数说明
        
        $var:mixed；正在评估的变量。
        
**返回值**

    boolean（$var是否类似于数组）

**示例**

```php
$arr = ['a'];
$rs = ArrayHelper::isTraversable($arr);//true

$arr2 = 'a';
$rs2 = ArrayHelper::isTraversable($arr);//false
```

## isSubset

**功能**

    检查数组或[[\Traversable]]是否是另一个数组的子集或[[\Traversable]]。
    
    如果$needle的所有元素都包含在$ haystack中，则此方法将返回true。如果缺少至少一个元素，则返回false。

**概要**

```php
public static function isSubset($needles, $haystack, $strict = false): ?bool
```

**描述**

    参数说明
        
        $needles:array|Traversable；必须**全部**的值在$haystack中。
        
        $haystack:array|Traversable；要搜索的值集。
        
        $strict:boolean；是否启用strict（`===`）比较。
        
**返回值**

    boolean（true如果$needle是$haystack的子集，否则为false。）

**异常**

    如果$haystack或$needle既不可遍历也不是数组，则返回InvalidArgumentException。

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::isSubset(['b', 'c'], $arr);//true

$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::isSubset(['b', 'd'], $arr);//false
```

## filter

**功能**

    根据指定的规则过滤数组。

**概要**

```php
public static function filter($array, $filters): array
```

**描述**

    参数说明
        
        $array:array；原数组
        
        $filters:array；定义应从结果中保留或删除的数组键的规则。
                        每条规则是：
                         - var  - $array['var']将留在结果中。
                         - var.key = 只有$array['var']['key']将留在结果中。
                         - !var.key =$array['var']['key']将从结果中删除。
        
**返回值**

    array（过滤的数组）

**示例**

```php
$arr = [
    'id' => 1,
    'description' => [
        'name' => 'swoft',
        'version' => '2.0'
    ]
];
$rs = ArrayHelper::filter($arr, ['id', 'description.version']);//['id'=>1,'description'=>['version'=>'2.0']]
```

## accessible

**功能**

    确定给定值是否可访问数组。

**概要**

```php
public static function accessible($value): bool
```

**描述**

    参数说明
        
        $value:mixed；
        
**返回值**

    boolean

**示例**

```php
$arr = ['a'];
$rs = ArrayHelper::accessible($arr);//true

$arr = 'a';
$rs = ArrayHelper::accessible($arr);//false
```

## exists

**功能**

    确定提供的数组中是否存在给定的键。

**概要**

```php
public static function exists($array, $key): bool
```

**描述**

    参数说明
        
        $array:ArrayAccess|array；
        
        $key:string|int；
        
**返回值**

    boolean

**示例**

```php
$arr = ['id' => 1, 'name' => 'swoft'];
$rs = ArrayHelper::exists($arr, 'name');//true
$rs2 = ArrayHelper::exists($arr, 'description');//false
```

## get

**功能**

    使用“点”表示法从数组中获取项目。

**概要**

```php
public static function get($array, $key = null, $default = null)
```

**描述**

    参数说明
        
        $array:ArrayAccess|array；
        
        $key:string；
        
        $default:mixed；
        
**返回值**

    mixed

**示例**

```php
$arr = ['id' => 1, 'name' => 'swoft'];
$rs = ArrayHelper::get($arr, 'name');//swoft
$rs2 = ArrayHelper::get($arr, 'description');//null
$rs3 = ArrayHelper::get($arr, 'description', '2.0');//'2.0'
```

## has

**功能**

    使用“点”表示法检查数组中是否存在项目。

**概要**

```php
public static function has($array, $key): bool
```

**描述**

    参数说明
        
        $array:ArrayAccess|array；
        
        $key:string；
        
**返回值**

    boolean

**示例**

```php
$arr = [
    'id' => 1,
    'description' => [
        'name' => 'swoft',
        'version' => '2.0'
    ]
];
$rs = ArrayHelper::has($arr, 'id');//true

$rs2 = ArrayHelper::has($arr, 'name');//false

$rs3 = ArrayHelper::has($arr, 'description.name');//true
```

## set

**功能**
    
    使用“点”表示法将数组项设置为给定值。如果没有为该方法指定key，则将替换整个数组。

**概要**

```php
public static function set(&$array, $key, $value): array
```

**描述**

    参数说明
        
        $array:array；
        
        $key:string；
        
        $value:mixed；
        
**返回值**

    array

**示例**

```php
$arr = ['id' => 1];
$rs = ArrayHelper::set($arr, 'name', 'swoft');//['id'=>1,'name'=>'swoft']
```

## insert

**功能**

    将一个数组插入另一个数组

**概要**

```php
public static function insert(array &$array, int $index, ...$insert): void
```

**描述**

    参数说明
        
        $array:array；
        
        $index:int；
        
        $insert:array；
        
**返回值**

    void

**示例**

```php
$arr = ['a', 'b', 'c'];
ArrayHelper::insert($arr, 2, 'd', 'e');//['a','b','d','e','c']
```

## wrap

**功能**

    如果给定的值不是数组而不是null，则将其包装在一个中。

**概要**

```php
public static function wrap($value): array
```

**描述**

    参数说明
        
        $value:mixed；
        
**返回值**

    array

**示例**

```php
$arr = ArrayHelper::wrap(['a']);//['a']

$arr2 = ArrayHelper::wrap('a');//['a']
```

## isArrayable

**功能**

    判断是否是数组或是否是Arrayable的对象。

**概要**

```php
public static function isArrayable($value): bool
```

**描述**

    参数说明
        
        $value:mixed；
        
**返回值**

    boolean

**示例**

```php
$arr = ['a'];
$rs = ArrayHelper::isArrayable($arr);//true

$arr = 'a';
$rs = ArrayHelper::isArrayable($arr);//false
```

## flatten

**功能**

    将多维数组展平为单个级别。

**概要**

```php
public static function flatten(array $array, int $depth = PHP_INT_MAX): array
```

**描述**

    参数说明
        
        $array:array；
        
        $depth:int；
        
**返回值**

    array

**示例**

```php
$arr = [
    'id' => 1,
    'description' => [
        'name' => 'swoft',
        'version' => '2.0'
    ]
];
$rs = ArrayHelper::flatten($arr);//[1,'swoft','2.0']
```

## findSimilar

**功能**

    从 array|Iterator中查找类似的文本。

**概要**

```php
public static function findSimilar(string $need, $iterator, int $similarPercent = 45): array
```

**描述**

    参数说明
        
        $need:string；
        
        $iterator:Iterator|array；
        
        $similarPercent:int；
        
**返回值**

    array

**示例**

```php
$arr = [
    'swoft',
    'swoft-2',
    'yii',
    'thinkphp',
    'test-swoft-cloud'
];
$rs = ArrayHelper::findSimilar('swoft', $arr);//['swoft','swoft-2','test-swoft-cloud']
```

## getKeyMaxWidth

**功能**

    获取关键最大宽度

**概要**

```php
public static function getKeyMaxWidth(array $data, bool $expectInt = false): int
```

**描述**

    参数说明
        
        $data:array；['key1'=>'value1','key2-test'=>'vaule2']
        
        $expectInt:bool；
        
**返回值**

        int

**示例**

```php
$arr = [
    'id' => 1,
    'name' => 'swoft',
    'version' => '2.0',
    'description' => 'php framework'
];
$rs = ArrayHelper::getKeyMaxWidth($arr);//11
```

## first

**功能**

    返回通过给定真值测试的数组中的第一个元素。

**概要**

```php
public static function first($array, callable $callback = null, $default = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $callback:callable|null；
        
        $default:mixed；
        
**返回值**

    mixed

**示例**

```php
$arr = ['a', 'b', 'c', 'd'];
$rs = ArrayHelper::first($arr, function ($value) {
    return $value == 'c';
});//c
```

## where

**功能**

    使用给定的回调过滤数组。

**概要**

```php
public static function where($array, callable $callback)
```

**描述**

    参数说明
        
        $array:array；
        
        $callback:callable；
        
**返回值**

    array

**示例**

```php
$arr = ['a', 'b', 'c', 'd'];
$rs = ArrayHelper::where($arr, function ($value) {
    return $value == 'c';
});//[2=>'c']
```

## query

**功能**

    将数组转换为查询字符串。

**概要**

```php
public static function query(array $array): string
```

**描述**

    参数说明
        
        $array:array；
        
**返回值**

    string

**示例**

```php
$arr = [
    'id' => 1,
    'name' => 'swoft',
];
$rs = ArrayHelper::query($arr);//'id=1&name=swoft'
```

## only

**功能**

    从给定数组中获取项目的子集。

**概要**

```php
public static function only(array $array, array $keys): array
```

**描述**

    参数说明
        
        $array:array；
        
        $keys:array；
        
**返回值**

    array

**示例**

```php
$arr = [
    'id' => 1,
    'name' => 'swoft',
];
$rs = ArrayHelper::only($arr, ['name']);//['name'=>'swoft']
```

## last

**功能**

    返回通过给定真值测试的数组中的最后一个元素。

**概要**

```php
public static function last($array, callable $callback = null, $default = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $callback:callable|null；
        
        $default:mixed；
        
**返回值**

    mixed

**示例**

```php
$arr = ['a', 'b', 'c', 'd'];
$rs = ArrayHelper::last($arr, function ($value) {
    return $value == 'c';
});//'c'
```

## pluck

**功能**

    从数组中获取值的数组。

**概要**

```php
public static function pluck($array, $value, $key = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $value:string|array；
        
        $key:string|array|null；
        
**返回值**

    array

**示例**

```php
$arr = [
    ['id' => 1, 'name' => 'swoft'],
    ['id' => 2, 'name' => 'yii'],
];
$rs = ArrayHelper::pluck($arr, 'name');//['swoft','yii']
```

## collapse

**功能**

    将数组的数组折叠成单个数组。

**概要**

```php
public static function collapse($array)
```

**描述**

    参数说明
        
        $array:array；
        
**返回值**

    array

**示例**

```php
$arr = [
    ['id' => 1, 'name' => 'swoft', 'version' => '2.0'],
    ['id' => 2, 'name' => 'yii'],
];
$rs = ArrayHelper::collapse($arr);//['id'=>2,'name'=>'yii','version'=>'2.0']
```

## crossJoin

**功能**

    交叉连接给定的数组，返回所有可能的排列。

**概要**

```php
public static function crossJoin(...$arrays)
```

**描述**

    参数说明
        
        $array:array；
        
**返回值**

    array

**示例**

```php
$arr = ['a'];
$arr2 = ['b'];

$rs = ArrayHelper::crossJoin($arr, $arr2);//['a','b']
```

## prepend

**功能**
    
    将一个项推到数组的开头。

**概要**

```php
public static function prepend($array, $value, $key = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $value:mixed；
        
        $key:mixed；
        
**返回值**

    array

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::prepend($arr, 'd');//['d','a','b','c']
```

## random

**功能**
    
    从数组中获取一个或指定数量的随机值。

**概要**

```php
public static function random($array, $number = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $number:int|null；
        
**返回值**

    mixed

**异常**

    InvalidArgumentException

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::random($arr);
in_array($rs,$arr);//true
```

## shuffle

**功能**
    
    对给定数组进行洗牌并返回结果。

**概要**

```php
public static function shuffle($array, $seed = null)
```

**描述**

    参数说明
        
        $array:array；
        
        $seed:int|null；
        
**返回值**

    array

**示例**

```php
$arr = ['a', 'b', 'c'];
$rs = ArrayHelper::shuffle($arr);
var_dump(count($arr) === count($rs));//true
```




