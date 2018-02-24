# 接口服务
定义接口并实现接口，才能提供RPC服务。

## 定义接口
服务提供方定义好接口格式，存放到公共的lib库里面，服务调用方，加载lib库，就能使用接口服务。

```php
/**
 * The interface of demo service
 *
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 */
interface DemoInterface
{
    /**
     * @param array $ids
     *
     * @return array
     *
     * <pre>
     * [
     *    'uid' => [],
     *    'uid2' => [],
     *    ......
     * ]
     * <pre>
     */
    public function getUsers(array $ids);

    /**
     * @param string $id
     *
     * @return array
     */
    public function getUser(string $id);

    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc");
}
```

> 接口定义和普通接口完全一致，唯一不一样的是，需要使用注解定义类似deferXxx方法，对应类里面方法且首字母大写。这种deferXxx方法，一般用于业务延迟收包和并发使用。

## 接口实现
一个接口，会存在多种不同的实现，通过一个版本号来标识是那个逻辑实现。

### 注解

**@Service**    

- version 定义接口版本，默认是0

### 实例

**实现版本1**    

```php
/**
 * Demo servcie
 *
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 *
 * @Service()
 */
class DemoService implements DemoInterface
{
    public function getUsers(array $ids)
    {
        return [$ids];
    }

    public function getUser(string $id)
    {
        return [$id];
    }

    /**
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
}
```

**实现版本1**    

```php
/**
 * Demo service
 *
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 * @Service(version="1.0.1")
 */
class DemoServiceV2 implements DemoInterface
{
    public function getUsers(array $ids)
    {
        return [$ids, 'version'];
    }

    public function getUser(string $id)
    {
        return [$id, 'version'];
    }

    /**
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
}
```

> 不同的实现，需要定义不同的唯一版本号，如果存在相同，加载之后的服务会覆盖之前的服务。实现也和普通接口实现一致，唯一不同的是，需要使用注解定义deferXxx方法。