# RESTful

RESTful操作很简单，使用RESTful前，需要定义内容解析器，默认值支持JSON解析。

## 自定义解析器

### 定义解析器

实现RequestParserInterface接口，并实现parser方法

```php
/**
 * the json parser of request
 *
 * @Bean()
 * @uses      RequestJsonParser
 * @version   2017年12月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestJsonParser implements RequestParserInterface
{
    /**
     * do parser
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parser(ServerRequestInterface $request): ServerRequestInterface
    {
        if ($request instanceof Request) {
            $bodyStream  = $request->getBody();
            $bodyContent = $bodyStream->getContents();
            $bodyParams  = JsonHelper::decode($bodyContent, true);
            return $request->withBodyParams($bodyParams);
        }

        return $request;
    }
}
```

### 配置解析器

修改配置文件 app\config\beans\base.php，可以配置多个解析，Swoft 会自动根据请求 Content-Type 值使用对应的内容解析器

```php
    return [
        // ...
        'requestParser' =>[
                    'class' => \Swoft\Web\RequestParser::class,
                    'parsers' => [
                        'application/json' => \Swoft\Web\RequestJsonParser::class
                    ]
                ],
        // ...
    ];
    
```


## 使用实例

```php
/**
 * restful和参数验证测试demo
 *
 * @Controller(prefix="/user")
 *
 * @uses      RestController
 * @version   2017年11月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RestController
{
    /**
     * 查询列表接口
     * 地址:/user/
     *
     * @RequestMapping(route="/user", method={RequestMethod::GET})
     */
    public function actionList()
    {
        return ['list'];
    }


    /**
     * 创建一个用户
     * 地址:/user
     *
     * @RequestMapping(route="/user", method={RequestMethod::POST,RequestMethod::PUT})
     *
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionCreate(Request $request)
    {
        $name = $request->input('name');

        $bodyParams = $request->getBodyParams();
        $bodyParams = empty($bodyParams) ? ["create", $name] : $bodyParams;

        return $bodyParams;
    }

    /**
     * 查询一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::GET})
     *
     * @param int $uid
     *
     * @return array
     */
    public function actionGetUser(int $uid)
    {
        return ['getUser', $uid];
    }

    /**
     * 查询用户的书籍信息
     * 地址:/user/6/book/8
     *
     * @RequestMapping(route="{userId}/book/{bookId}", method={RequestMethod::GET})
     *
     * @param int    $userId
     * @param string $bookId
     *
     * @return array
     */
    public function actionGetBookFromUser(int $userId, string $bookId)
    {
        return ['bookFromUser', $userId, $bookId];
    }

    /**
     * 删除一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::DELETE})
     *
     * @param int $uid
     *
     * @return array
     */
    public function actionDeleteUser(int $uid)
    {
        return ['delete', $uid];
    }

    /**
     * 更新一个用户信息
     * 地址:/user/6
     *
     * @RequestMapping(route="{uid}", method={RequestMethod::PUT, RequestMethod::PATCH})
     *
     * @param int $uid
     * @param Request $request
     * @return array
     */
    public function actionUpdateUser(Request $request, int $uid)
    {
        $body = $request->getBodyParams();
        $body['update'] = 'update';
        $body['uid'] = $uid;

        return $body;
    }
```