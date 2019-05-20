# 声明服务

# 接口服务

定义接口并实现接口，才能提供RPC服务。

## 目录定义

官方应用中给出的目录如下：

```text
app/
  Rpc/
    - Lib/          // 服务的公共接口定义目录，里面通常只有php接口类
    - Services/     // 具体的服务接口实现类，里面的类通常实现了 Lib 中定义的接口
```

> 当然在多个服务中使用时， 要将lib库 `app/Rpc/Lib` 移到一个公共的git仓库里，然后各个服务通过 composer 来获取使用

## 定义接口

服务提供方定义好接口格式，存放到公共的lib库里面，服务调用方，加载lib库，就能使用接口服务，接口定义和普通接口完全一致。

```php
**
 * Class UserInterface
 *
 * @since 2.0
 */
interface UserInterface
{
    /**
     * @param int   $id
     * @param mixed $type
     * @param int   $count
     *
     * @return array
     */
    public function getList(int $id, $type, int $count = 10): array;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @return string
     */
    public function getBigContent(): string;
}
```

## 接口实现

一个接口，会存在多种不同的实现，通过一个版本号来标识是那个逻辑实现。

### 注解

**@Service**    

- version 定义接口版本，默认是 `1.0`

### 实例

**实现版本1**    

```php
/**
 * Class UserService
 *
 * @since 2.0
 *
 * @Service()
 */
class UserService implements UserInterface
{
    /**
     * @param int   $id
     * @param mixed $type
     * @param int   $count
     *
     * @return array
     */
    public function getList(int $id, $type, int $count = 10): array
    {
        return ['name' => ['list']];
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBigContent(): string
    {
        $content = Co::readFile(__DIR__ . '/big.data');
        return $content;
    }
}
```

**实现版本2**    

```php
/**
 * Class UserServiceV2
 *
 * @since 2.0
 *
 * @Service(version="1.2")
 */
class UserServiceV2 implements UserInterface
{
    /**
     * @param int   $id
     * @param mixed $type
     * @param int   $count
     *
     * @return array
     */
    public function getList(int $id, $type, int $count = 10): array
    {
        return [
            'name' => ['list'],
            'v'    => '1.2'
        ];
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBigContent(): string
    {
        $content = Co::readFile(__DIR__ . '/big.data');
        return $content;
    }
}
```

> 不同的实现，需要定义**不同的唯一版本号**，如果存在相同，加载之后的服务会覆盖之前的服务

