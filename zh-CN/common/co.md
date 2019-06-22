# 协程函数

框架中封装了一些协程操作的函数，方便开发者调用

## 创建协程
<p class="tip"> Swoft 框架中一定不要直接使用 Swoole 提供的 `go` 函数创建协程，否则会导致请求和上下文丢失导致一些奇葩问题。 </p>

```
use Swoft\Co;

Co::create(function(){
    // to do
});
```

如上是框架中创建协程的原型，框架也提供了更为简单的 `sgo` 函数来创建协程，替换 Swoole 的 `go` 函数使用。

```php
sgo(function(){
    // todo
});
```

<p class="tip"> `sgo` 函数使用和 Swoole `go` 函数完全一样，切记框架中只能使用 `sgo` 函数，不能直接使用 `go` 函数 </p>


## 协程ID

获取当前协程ID, -1 非协程环境

```php
use Swoft\Co;

$id = Co::id();
```

## 顶级协程ID

获取顶级(最外层协程ID)

```php
use Swoft\Co;

$id = Co::tid();
```

## 读取文件

全量读取文件

```php
public static function readFile(string $filename): string
```

- 读取的文件路径

读取成功返回字符串内容，读取失败返回 false，可使用swoole_last_error获取错误信息。readFile方法没有尺寸限制，读取的内容会存放在内存中，因此读取超大文件时可能会占用过多内存

使用实例：

```php
use Swoft\Co;

$fileName = 'test.file';
$data = Co::readFile($fileName);
```

## 写文件

```php
public static function writeFile(string $filename, string $data, int $flags = null): int
```

- filename为文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
- $fileContent为要写入到文件的内容，最大可写入4M
- flags为写入的选项，默认会清空当前文件内容，可以使用FILE_APPEND表示追加到文件末尾

写入成功返回true，写入失败返回false

```php
use Swoft\Co;

$fileName = 'test.file';
$data = Co::writeFile($fileName, 'data');
```


## 并发

框架底层通过协程通道，封装了一套多混合IO并发操作的方法，一般用于多个流程没有依赖，可以并发执行，提高执行效率。

```php
public static function multi(array $requests, float $timeout = 0): array
```

- requests 多个操作集合，KV 数组格式
- timeout 超时时间，默认永久超时

并发执行结果，按照 requests 集合数组 key 对应关系返回，如果一个 key 对应的值返回 false, 意味着该操作执行失败. requests 里面的每个操作可以执行的业务无上限，根据自己的业务而定。

requests 格式支持多种方式

- 对象的某个方法
- 对象的静态方法
- 闭包匿名函数

使用实例如下：

```php
use Swoft\Co;

/**
 * Class CoTest
 *
 * @since 2.0
 */
class CoTest
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testMulti()
    {
        $requests = [
            'method'       => [$this, 'requestMethod'],
            'staticMethod' => "SwoftTest\Unit\CoTest::requestMehtodByStatic",
            'closure'      => function () {
                $cli = new Client('www.baidu.com', 80);
                $cli->get('/');
                $result = $cli->body;
                $cli->close();

                return $result;
            }
        ];

        $response = Co::multi($requests);
    }

    /**
     * @return mixed
     */
    public function requestMethod()
    {
        $cli = new Client('www.baidu.com', 80);
        $cli->get('/');
        $result = $cli->body;
        $cli->close();

        return $result;
    }

    /**
     * @return mixed
     */
    public static function requestMehtodByStatic()
    {
        $cli = new Client('www.baidu.com', 80);
        $cli->get('/');
        $result = $cli->body;
        $cli->close();

        return $result;
    }
}
```
