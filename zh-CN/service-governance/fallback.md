# 服务降级

服务降级，其实对功能的一种容错机制，这里指的是RPC调用服务降级和普通的服务降级。

## 普通服务降级
比如内部服务调用失败后，为不影响正常请求，可以实现 Fallback，实现对服务的降级。Fallback 定义有目前有三种形式。

- 全局函数
- 匿名函数
- 可以调用的 Callback

```php
// 全局函数
function method()
{
    // ..
}

// 匿名函数

$fallback = function(){
                // ...
            };

// callback
class xxx
{
  public function method()
  {
  
  }
}

```

## RPC服务降级

### 注解

**@Fallback**
    
定义RPC服务降级处理的接口实现。

- name 降级功能名称，缺省使用类名

### 定义降级服务

```php
/**
 * Fallback demo
 *
 * @Fallback("demoFallback")
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 */
class DemoServiceFallback implements DemoInterface
{
    public function getUsers(array $ids)
    {
        return ['fallback', 'getUsers', func_get_args()];
    }

    public function getUser(string $id)
    {
        return ['fallback', 'getUser', func_get_args()];
    }

    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
    {
        return ['fallback', 'getUserByCond', func_get_args()];
    }
}
```

- 需要使用注解定义defer函数，此处不做详细描述，请查看RPC客户单章节
- DemoInterface 是RPC章节的lib接口，每个接口可以实现多个不用的降级服务
- RPC服务调用失败后，就会调用实现的服务降级里面函数

### 使用实例

```php
/**
 * rpc controller test
 *
 * @Controller(prefix="rpc")
 */
class RpcController
{

    /**
     * @Reference(name="user", fallback="demoFallback")
     *
     * @var DemoInterface
     */

    /**
     * @return array
     */
    public function fallback()
    {
        $result1  = $this->demoService->getUser('11');
        $result2  = $this->demoService->getUsers(['1','2']);
        $result3  = $this->demoService->getUserByCond(1, 2, 'boy', 1.6);

        return [
            $result1,
            $result2,
            $result3,
        ];
    }

    /**
     * @return array
     */
    public function deferFallback()
    {
        $result1  = $this->demoService->deferGetUser('11')->getResult();
        $result2  = $this->demoService->deferGetUsers(['1','2'])->getResult();
        $result3  = $this->demoService->deferGetUserByCond(1, 2, 'boy', 1.6)->getResult();

        return [
            'defer',
            $result1,
            $result2,
            $result3,
        ];
    }
}
```

- @Reference注解，指定fallback服务降级实现，如果不是同一个接口实现，会抛出异常






