# Acl
我们的权限配置是通过配合的token进行实现的
只要我们的请求中包含如下的header值

```php
Authorization: Bearer {token}.
```
那么我们的中间件就可以识别并解析token,下面看看如何实现

## AuthServiceInterface

首先,实现我们自己的AuthService,继承自系统默认的

```php
    /**
     * Class ManagerService
     * @package App\Domain\User\Service
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
            echo sprintf(" 你访问了: %s",$requestHandler);
            if($id){
                return true;
            }
            return false;
        }
    
    }
```

在 config/beans/base 中的 添加如下代码把系统默认的进行替换

```php
\Swoft\Auth\Mapping\AuthServiceInterface::class=>[
        'class'=>App\Domain\User\Service\AuthService::class
    ]
```

然后在你想要进行权限控制的Controller 上面添加 AclMiddleware::class 中间件,如下

```php
/**
     * @Middleware(AclMiddleware::class)
     * 视图渲染demo - 没有使用布局文件
     * @View(template="demo/view")
     * @RequestMapping(route="/", method={RequestMethod::GET})
     */
    public function view()
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

然后访问上面的路由 / ,就可以在控制台看到  你访问了: ... 

在AuthService 中的auth 里面你可以获取用户的id,和你自己定义的附加字段,如我们上面例子basicAuth里的 role,这个前提是这个访问正常的添加了 Authorization: Bearer {token}.