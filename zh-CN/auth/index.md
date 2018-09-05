# 权限控制
现在支持:
- BasicAuth 
- BearerToken (JWT)
- ACL

## 安装:
  
```php
composer require swoft/auth
```
  
## 使用

本组件目前实现了 `BasicAuth` 和 `BearerToken` 的验证,以及简单的 `ACL`，使用方法简单，在 `config/beans/base.php` 中的 `serverDispatcher.middlewares` 里 添加 `\Swoft\Auth\Middleware\AuthMiddleware::class` 中间件，如下

```php
'serverDispatcher' => [
    'middlewares' => [
        \Swoft\Auth\Middleware\AuthMiddleware::class,
    ]
],
```

然后在配置文件 `config/properties/app.php` 中添加

```php
'auth' => [
    'jwt' => [
       'algorithm' => 'HS256',
       'secret' => '1231231'
    ],
],
```
- 注意 `secret` 不要使用上述值，修改为你自己的值

## 配置验证管理 AuthManagerInterface

`AuthManager` 是登录验证的核心，本类实现了 `Token` 的验证及缓存，你可以继承这个类实现多种方式登录(配合`accountType`实现)，下面就是一个 `basicAuth` 的 `Demo`

首先实现一个 `Swoft\Auth\Mapping\AccountTypeInterface` 作为我们登录的通道

```php
use Swoft\Auth\Mapping\AccountTypeInterface;
use Swoft\Auth\Bean\AuthResult;

/**
 * @Bean()
 */
class AdminNormalAccount implements AccountTypeInterface
{
    /**
     * @Inject()
     * @var AdminUserDAO
     */
    protected $dao;

    const ROLE = 'role';

    /**
     * @throws \Swoft\Db\Exception\DbException
     */
    public function login(array $data) : AuthResult
    {
        $username = $data[self::LOGIN_IDENTITY];
        $password = $data[self::LOGIN_CREDENTIAL];
        $user = $this->dao::findOneByUsername($username);
        $res = new AuthResult();
        if($user instanceof AdminUserBean && $user->verify($password)){
            $res->setExtendedData([self::ROLE => $user->getIsAdministrator()]);
            $res->setIdentity($user->getId());
        }
        return $res;
    }

    /**
     * @throws \Swoft\Db\Exception\DbException
     */
    public function authenticate(string $identity) : bool
    {
        return $this->dao::issetUserById($identity);
    }
}
```

然后在我们自己的 `AuthManagerService` 实现这个登录

```php
use Swoft\Auth\AuthManager;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Auth\Bean\AuthSession;

/**
 * @Bean()
 */
class AuthManagerService extends AuthManager implements AuthManagerInterface
{
      /**
     * @var string
     */
    protected $cacheClass = Redis::class;

    /**
     * @var bool 开启缓存
     */
    protected $cacheEnable = true;

    public function adminBasicLogin(string $username, string $password) : AuthSession
    {
        return $this->login(AdminNormalAccount::class, [
            'identity' => $username,
            'crendential' => $password
        ]);
    }
}
```

然后在 `config/beans/base.php` 中把系统默认的 `AuthManager` Bean 替换为我们自己的 `AuthManagerService`，添加如下代码进行替换

```php
\Swoft\Auth\Mapping\AuthManagerInterface::class => [
    'class'=>App\Domain\User\Service\AuthManagerService::class
],
```

现在我们就可以在一个 `Controller` 中使用刚才实现的登录方式了

```php
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Auth\Mapping\AuthManagerInterface;

/**
 * @Controller(prefix="/oauth")
 */
class AuthorizationsResource
{
     /**
     * @RequestMapping(route="token", method={RequestMethod::POST})
     */
    public function oauth(Request $request) : array
    {
        $username = $request->getAttribute(AuthConstants::BASIC_USER_NAME) ?? '';
        $password = $request->getAttribute(AuthConstants::BASIC_PASSWORD) ?? '';
        if(!$username || !$password){
            return [
                "code" => 400,
                "message" => "Basic Auth:{username,password}"
            ];
        }
        /** @var AuthManagerService $manager */
        $manager = App::getBean(AuthManagerInterface::class);
        /** @var AuthSession $session */
        $session = $manager->adminBasicLogin($username, $password);
        $data = [
            'token' => $session->getToken(),
            'expire' => $session->getExpirationTime()
        ];
        return $data;
    }
}

```

现在可以通过 Postman 请求我们的登录接口了
