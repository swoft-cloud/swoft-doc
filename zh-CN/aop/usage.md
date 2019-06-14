# 使用

本章以计算类里面每个方法执行时间为例，简单介绍使用切面编程。

## 编写测试类

首先先编写一个Http Controller 类

> App\Http\Controller\TestExecTimeController

```PHP
<?php

namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * 运算时间测试类
 *
 * @Controller()
 */
class TestExecTimeController
{
    /**
     * 闭包递归 计算阶乘
     *
     * @RequestMapping(route="test/{number}")
     *
     * @param int $number
     *
     * @return array
     */
    public function factorial(int $number): array
    {
        $factorial = function ($arg) use (&$factorial) {
            if ($arg == 1) {
                return $arg;
            }
            
            return $arg * $factorial($arg - 1);
        };

        return [$factorial($number)];
    }


    /**
     * 计算1～1000的和，最后休眠1s
     * 
     * @RequestMapping()
     */
    public function sumAndSleep(): array
    {
        $sum = 0;
        for ($i = 1; $i <= 1000; $i++) {
            $sum = $sum + $i;
        }

        usleep(1000);
        return [$sum];
    }
}
```

## 编写切面类

创建一个切面类，对我们的测试类进行切入。

> App\Aspect\CalcExecTimeAspect

```PHP
<?php declare(strict_types=1);

namespace App\Aspect;

use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;

/**
 * AOP切面类
 * 
 * @since 2.0
 *
 * 声明切面类
 * @Aspect(order=1)
 *
 * 声明为 PointBean 类型的切面
 * @PointBean(include={"App\Http\Controller\TestExecTimeController"})
 */
class CalcExecTimeAspect
{
    protected $start;

    /**
     * 定义通知点
     * @Before()
     */
    public function before()
    {
        $this->start = microtime(true);
    }

    /**
     * 定义通知点
     * @After()
     */
    public function after(JoinPoint $joinPoint)
    {
        $method = $joinPoint->getMethod();
        $after = microtime(true);
        $runtime = ($after - $this->start) * 1000;
        
        echo "{$method} 方法，本次执行时间为: {$runtime}ms\n";
    }
}
```

启动 swoft 之后正常的访问我们的测试控制器，将会在控制器看到输出

> 访问控制器

```SHEL
$ curl http://localhost:18306/testExecTime/test/10
[3628800]%

$ curl http://localhost:18306/testExecTime/sumAndSleep
[500500]%
```

> 控制台输出

```SHELL
factorial 方法，本次执行时间为: 0.10013580322266ms
```
