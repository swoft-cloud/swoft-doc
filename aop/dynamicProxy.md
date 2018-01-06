# 动态代理

代理是一种常用的设计模式，其目的就是为其他对象提供一个代理以控制对某个对象的访问。代理类负责为委托类预处理消息，过滤消息并转发消息，以及进行消息被委托类执行后的后续处理。

- 静态代理，由程序员创建代理类或特定工具自动生成源代码再对其编译
- 动态代理，在程序运行时运用反射机制动态创建而成。

## 定义代理处理器

继承Swoft\Proxy\Handler\HandlerInterface接口，实现invoke方法。

```php
/**
 *
 *
 * @uses      TestHandler
 * @version   2017年12月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TestHandler implements HandlerInterface
{
    /**
     * @var object
     */
    private $target;

    public function __construct($target)
    {
        $this->target = $target;
    }

    public function invoke($method, $parameters)
    {
        $before = 'before';
        $result = $this->target->$method(...$parameters);
        $after = 'after';
        $result .= $before.$after;
        return $result;
    }
}
```

## 使用代理

```php

$object  = new ProxyTest(1, 2);
$handler = new TestHandler($object);

/* @var ProxyTest $proxy 这个代理类具有ProxyTest所有功能，可以直接当ProxyTest实例使用，没有任何区别*/
$proxy = Proxy::newProxyInstance(ProxyTest::class, $handler);

```