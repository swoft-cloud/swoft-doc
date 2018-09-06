# ACL
我们的权限配置是通过配合的`Token`进行实现的
只要我们的请求中包含如下的`header`值

```php
Authorization: Bearer {token}.
```
那么 `Auth组件` 的中间件就可以识别并解析 `Token`了,下面看看如何实现

## AuthServiceInterface

首先，实现我们自己的`AuthService`，继承自系统默认的`AuthUserService`并实现`Swoft\Auth\Mapping\AuthServiceInterface`接口

```php
use Swoft\Auth\Mapping\AuthServiceInterface;
use Swoft\Auth\AuthUserService;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @Bean()
 */
class AuthService extends AuthUserService implements AuthServiceInterface
{
    /**
     * @Inject()
     * @var OrdinaryUserDAO
     */
    protected $ordinaryDao;

    public function auth(string $requestHandler, ServerRequestInterface $request): bool
    {
        $id = $this->getUserIdentity();
        $role = $this->getUserExtendData()['role'] ?? '' ;
        echo sprintf("你访问了: %s",$requestHandler);
        if($id){
            return true;
        }
        return false;
    }

}
```

在 `config/beans/base.php` 中添加如下代码把系统默认的`Swoft\Auth\Mapping\AuthServiceInterface` Bean 替换掉

```php
\Swoft\Auth\Mapping\AuthServiceInterface::class => [
    // 你的 AuthService 的完整命名空间
    'class' => \App\Domain\User\Service\AuthService::class,
]
```

然后在你想要进行权限控制的 `Controller`上面添加 `Swoft\Auth\Middleware\AclMiddleware::class` 中间件，参考如下

```php
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Auth\Middleware\AclMiddleware;

/**
 * @Middleware(AclMiddleware::class)
 * @RequestMapping(route="/", method={RequestMethod::GET})
 */
public function index()
{
    $data = [
        'name' => 'Swoft',
        'repo' => 'https://github.com/swoft-cloud/swoft',
        'doc' => 'https://doc.swoft.org/',
        'doc1' => 'https://swoft-cloud.github.io/swoft-doc/',
        'method' => __METHOD__,
    ];
    return $data;
}
```

然后访问上面的路由 `/` ,就可以在控制台看到 `你访问了: ...`

在 `AuthService` 中的 `auth` 里面你可以获取用户的 ID，和自定义的附加字段，如我们上面例子里的 `role` 字段，这个前提是这个请求添加了授权的 `Token`
