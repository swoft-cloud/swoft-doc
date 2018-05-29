# AOP

AOP 为Aspect Oriented Programming的缩写，意为：面向切面编程，通过预编译方式和运行期动态代理实现程序功能的统一维护的一种技术。

## AOP 用途

利用AOP可以对业务逻辑的各个部分进行隔离，从而使得业务逻辑各部分之间的耦合度降低，提高程序的可重用性，同时提高开发的效率。

- 日志记录
- 性能统计
- 安全控制
- 事务处理
- 异常处理
- ... ... 等等

### 为何要使用AOP

主要意图：

将上面的非逻辑性的代码从业务逻辑代码中划分出来，通过对这些行为的分离，我们希望可以将它们独立到非指导业务逻辑的方法中，进而改变这些行为的时候不影响业务逻辑的代码。

## 组成部分

AOP 由 `切面`、`通知`、`连接点`、`切入点`，四部分组成。

### 切面（Aspect)  

其实就是共有功能的实现。如日志切面、权限切面、事务切面等。在实际应用中通常是一个存放共有功能实现的普通PHP类(**切面类**)，之所以能被AOP容器识别成切面，是在配置中指定的。

### 通知（Advice）

是切面的具体实现。以 **目标方法** （要被代理的方法）为参照点，根据放置的地方不同，可分为前置通知（`Before`）、后置通知（`AfterReturning`）、异常通知（`AfterThrowing`）、最终通知（`After`）与环绕通知（`Around`）5种。在实际应用中通常是指向**切面类**中的一个方法，具体属于哪类通知，同样是在配置中指定的。

### 连接点（Joinpoint）  

就是程序在运行过程中能够插入切面的地点。例如，方法调用、异常抛出或字段修改等，但Swoft只支持方法级的连接点。

### 切入点（Pointcut)
 
用于定义通知应该切入到哪些连接点上。不同的通知通常需要切入到不同的连接点上，这种精准的匹配是由切入点的正则表达式来定义的。

## 注解Tag

### 定义切面类

**@Aspect()**    

定义一个类为切面类

**@PointBean()**    

定义bean切入点 - 这个bean类里的方法执行都会经过此切面类的代理

- `include` 定义需要切入的实体名称集合
- `exclude` 定义需要排除的实体名称集合

**@PointAnnotation()**  
  
定义注解切入点 - 所有包含使用了对应注解的方法都会经过此切面类的代理

- `include` 定义需要切入的_注解名称_集合
- `exclude` 定义需要排除的注解集合

**@PointExecution()**    

定义匹配切入点 - 指明要代理目标类的哪些方法

- `include` 定义需要切入的匹配集合，匹配的类方法，支持正则表达式
- `exclude` 定义需要排序的匹配集合，匹配的类方法，支持正则表达式

正则表达式 - 通过正则匹配需要代理的方法

### 定义通知点

上面的几个tag，标明了切面类的作用范围。下面的几个tag则是进一步限制(或者说标记)要在那些点上进行切入

**@Before()**    

标记方法为前置通知 - 在目标方法执行前先执行此方法

**@After()**    

标记方法为后置通知 - 在目标方法执行后执行此方法

**@AfterReturning()**    

标记方法为最终返回通知

**@AfterThrowing()**    

标记方法为异常通知 - 在目标方法执行抛出异常时执行此方法

**@Around()**    

标记方法为环绕通知 - 在目标方法执行前、后都执行此方法

## 说明

- 定义切面后，框架会自动在对应的切入点，查询方法
- `@PointBean`、`@PointAnnotation`、`@PointExecution` 三种定义的关系是**并集**，三种里面定义的排除也是并集后在排除

> 提示： 为了便于理解和使用，一个切面类尽量只使用上面三个中的一个。

## 使用实例

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


