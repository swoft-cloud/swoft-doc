# 接口服务

定义接口并实现接口，才能提供RPC服务。

## 目录定义

官方应用中给出的目录如下：

```text
app/
  - Lib/          // 服务的公共接口定义目录，里面通常只有php接口类
  - Pool/         // 服务池配置，里面可以配置不同服务的连接池，参考里面的 UserServicePool
  - Services/     // 具体的服务接口实现类，里面的类通常实现了 Lib 中定义的接口
```

> 当然在多个服务中使用时， 要将lib库 `app/Lib` 移到一个公共的git仓库里，然后各个服务通过 composer 来获取使用

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

接口定义和普通接口完全一致，唯一不一样的是

- 需要在类注释上定义类似 `deferGetUser` 方法，对应类里面方法 `getUser` 且首字母大写。这种 `defer*` 方法，一般用于业务延迟收包和并发使用。
- 这些方法是不需要实现的，仅用于提供IDE提示。内部调用逻辑由框架帮你完成

## 接口实现

一个接口，会存在多种不同的实现，通过一个版本号来标识是那个逻辑实现。

### 注解

**@Service**    

- version 定义接口版本，默认是 `0`

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
 * @Service() // 实现了接口 DemoInterface，版本号为 0
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

**实现版本2**    

```php
/**
 * Demo service
 *
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 * @Service(version="1.0.1") // 实现了接口 DemoInterface，版本号为 1.0.1
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

> 不同的实现，需要定义**不同的唯一版本号**，如果存在相同，加载之后的服务会覆盖之前的服务

