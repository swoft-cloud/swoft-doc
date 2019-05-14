# 声明

## 声明切面

Aspect 类与任何其他正常的 bean 类似，并且可能像任何其他类一样拥有方法和字段，但它们将使用 @Aspect 注释，如下所示:

```php
use Swoft\Aop\Annotation\Mapping\Aspect;

/**
 * Class DemoAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 */
class DemoAspect
{
    // ...
}
```

### @Aspect

定义一个类为切面类

- order 优先级，多个切面，越小预先执行

## 声明切入点

一个切入点有助于确定不同的意见执行感兴趣的连接点（即方法）。

```php
/**
 * Class DemoAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointBean(
 *     include={"testOrderAop"}
 * )
 */
class DemoAspect
{
    // ...
}
```

### @PointBean

定义bean切入点, 这个bean类里的方法执行都会经过此切面类的代理

- include 定义需要切入的实体名称集合
- exclude 定义需要排除的实体名称集合

### @PointAnnotation

定义注解切入点, 所有包含使用了对应注解的方法都会经过此切面类的代理

- include 定义需要切入的_注解名称_集合
- exclude 定义需要排除的注解集合

### PointExecution

定义匹配切入点, 指明要代理目标类的哪些方法

- include 定义需要切入的匹配集合，匹配的类方法，支持正则表达式
- exclude 定义需要排序的匹配集合，匹配的类方法，支持正则表达式

> @PointBean、@PointAnnotation、@PointExecution 三种定义的关系是并集，三种里面定义的排除也是并集后在排除。建议为了便于理解和使用，一个切面类尽量只使用上面三个中的一个

## 声明通知

```php
use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;

/**
 * Class DemoAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointBean(
 *     include={"testOrderAop"}
 * )
 */
class DemoAspect
{
    /**
     * @Before()
     */
    public function before()
    {
        // before
    }

    /**
     * @After()
     */
    public function after()
    {
        // After
    }

    /**
     * @AfterReturning()
     *
     * @param JoinPoint $joinPoint
     *
     * @return mixed
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $ret = $joinPoint->getReturn();
        
        // After return

        return $ret;
    }

    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // Before around
        $result = $proceedingJoinPoint->proceed();
        // After around
        
        return $result;
    }

    /**
     * @param \Throwable $throwable
     *
     * @AfterThrowing()
     */
    public function afterThrowing(\Throwable $throwable)
    {
        // afterThrowing
    }
}
```

- @Before 前置通知，在目标方法执行前先执行此方法
- @After 后置通知，在目标方法执行后执行此方法
- @AfterReturning 最终返回通知
- @AfterThrowing 异常通知，在目标方法执行抛出异常时执行此方法
- @Around 环绕通知，在目标方法执行前、后都执行此方法