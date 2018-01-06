# AOP切面编程

### 切面（Aspect）

其实就是共有功能的实现。如日志切面、权限切面、事务切面等。在实际应用中通常是一个存放共有功能实现的普通PHP类，之所以能被AOP容器识别成切面，是在配置中指定的。

### 通知（Advice）

是切面的具体实现。以目标方法为参照点，根据放置的地方不同，可分为前置通知（Before）、后置通知（AfterReturning）、异常通知（AfterThrowing）、最终通知（After）与环绕通知（Around）5种。在实际应用中通常是切面类中的一个方法，具体属于哪类通知，同样是在配置中指定的。

### 连接点（Joinpoint）

就是程序在运行过程中能够插入切面的地点。例如，方法调用、异常抛出或字段修改等，但Spring只支持方法级的连接点。

### 切入点（Pointcut）

用于定义通知应该切入到哪些连接点上。不同的通知通常需要切入到不同的连接点上，这种精准的匹配是由切入点的正则表达式来定义的。

## 定义切面

### @Aspect()

- 定义一个类为切面

### @PointBean()

- 定义bean切入点
- include 定义需要切入的实体名称集合
- exclude 定义需要排除的实体名称集合

### @PointAnnotation()

- 定义注解切入点
- include 定义需要切入的注解名称集合
- exclude 定义需要排除的注解集合

### @PointExecution()

- 定义匹配切入点
- include 定义需要切入的匹配集合
- exclude 定义需要排序的匹配集合

### @Before()

- 标记方法为前置通知

### @After()

- 标记方法为后置通知

### @AfterReturning()

- 标记方法为最终返回通知

### @AfterThrowing()

- 标记方法为异常通知

### @Around()

- 标记方法为环绕通知


## 定义切面

定义切面后，会自动切入方法，不需其它配置操作

```php
/**
 * the test of aspcet
 *
 * @Aspect()
 *
 * @PointBean(
 *     include={AopBean::class},
 * )
 * @PointAnnotation(
 *     include={
 *      Cacheable::class,
 *      CachePut::class
 *      }
 *  )
 * @PointExecution(
 *     include={
 *      "Swoft\Testing\Aop\RegBean::reg.*",
 *     }
 * )
 *
 * @uses      AllPointAspect
 * @version   2017年12月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AllPointAspect
{
    /**
     * @Before()
     */
    public function before()
    {
        var_dump(' before1 ');
    }

    /**
     * @After()
     */
    public function after()
    {
        var_dump(' after1 ');
    }

    /**
     * @AfterReturning()
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        return $result.' afterReturn1 ';
    }

    /**
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->test .= ' around-before1 ';
        $result = $proceedingJoinPoint->proceed();
        $this->test .= ' around-after1 ';
        return $result.$this->test;
    }

    /**
     * @AfterThrowing()
     */
    public function afterThrowing()
    {
        echo "aop=1 afterThrowing !\n";
    }
}
```


> @PointBean、@PointAnnotation、@PointExecution 三种定义的关系是并集，三种里面定义的排除也是并集后在排除












