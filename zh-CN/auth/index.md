# 权限控制

    现在支持:
   
  - BasicAuth 
  - BearerToken  (JWT)
  - Acl

## 安装:
  
  ```php
  
    composer require swoft/auth
    
  ```
  
## 使用

本组件目前实现了BasicAuth 和BearerToken的验证,以及简单的Acl,使用方法简单,在 config/beans/base 中的 middlewares 里 添加 \Swoft\Auth\Middleware\AuthMiddleware::class 中间件,如下图

```php
'serverDispatcher' => [
      'middlewares' => [
            \Swoft\Auth\Middleware\AuthMiddleware::class,
             ...
            ]
        ],
```

然后在配置文件config/properties/app中添加

```php
'auth'=>[
     'jwt'=>[
          'algorithm'=>'HS256',
          'secret'=>'1231231'
           ]
      ],
```
- 注意secret 不要使用上述值,修改为你自己的值

## 配置验证管理 AuthManagerInterface

AuthManager 是登录验证的核心,本类实现了token的验证及缓存,你可以继承这个类实现多种方式登录(配合accountType实现),下面就是一个basicAuth的demo

首先实现一个AccountTypeInterface 作为我们登录的通道

```php
    /**
     * Class AdminNormalAccount
     * @package App\Component\Auth\Account
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
         * @param array $data
         * @return AuthResult|null|\Swoft\Auth\Bean\AuthResult
         * @throws \Swoft\Db\Exception\DbException
         */
        public function login(array $data):AuthResult
        {
            $username = $data[self::LOGIN_IDENTITY];
            $password = $data[self::LOGIN_CREDENTIAL];
            $user = $this->dao::findOneByUsername($username);
            $res = new AuthResult();
            if($user instanceof AdminUserBean && $user->verify($password)){
                $res->setExtendedData([self::ROLE=>$user->getIsAdministrator()]);
                $res->setIdentity($user->getId());
            }
            return $res;
        }
    
        /**
         * @param string $identity Identity
         *
         * @return bool Authentication successful
         * @throws \Swoft\Db\Exception\DbException
         */
        public function authenticate(string $identity):bool
        {
            return $this->dao::issetUserById($identity);
        }
    }
```
然后在我们自己的 AuthManagerService 实现这个登录
```php
    /**
     * Class AuthManagerService
     * @package App\Domain\User\Service
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
    
        /**
         * @param string $username
         * @param string $password
         * @return \Swoft\Auth\Bean\AuthSession
         */
        public function adminBasicLogin(string $username,string $password){
            return $this->login(AdminNormalAccount::class,[
                AdminNormalAccount::LOGIN_DATA_USERNAME=>$username,
                AdminNormalAccount::LOGIN_DATA_PASSWORD=>$password
            ]);
        }
    }

```

然后在 config/beans/base 中把系统默认的AuthManager替换为我们自己的AuthManagerService的 ,添加如下代码进行替换

```php
     \Swoft\Auth\Mapping\AuthManagerInterface::class=>[
            'class'=>App\Domain\User\Service\AuthManagerService::class
        ],
```
现在我们就可以在一个controller 中使用刚才实现的登录方式了

```php
/**
 * Class AuthorizationsResource
 * @package App\Controllers\Resource
 * @Controller(prefix="/oauth")
 */
class AuthorizationsResource
{
     /**
         * @RequestMapping(route="token", method={RequestMethod::POST})
         * @param Request $request
         * @return array
         */
        public function oauth(Request $request)
        {
            $username = $request->getAttribute(AuthConstants::BASIC_USER_NAME) ?? '';
            $password = $request->getAttribute(AuthConstants::BASIC_PASSWORD) ?? '';
            if(!$username || !$password){
                return [
                    "code"=>ErrorCodes::POST_DATA_NOT_PROVIDED,
                    "message"=>"Basic Auth:{username,password}"
                ];
            }
            /** @var AuthManagerService $manager */
            $manager = App::getBean(AuthManagerInterface::class);
            /** @var AuthSession $session */
            $session = $manager->adminBasicLogin($username,$password);
            $data = [
                'token'=>$session->getToken(),
                'expire'=>$session->getExpirationTime()
            ];
            return $data;
        }

}

```

现在可以通过postman请求我们的登录接口了