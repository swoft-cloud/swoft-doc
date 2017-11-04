# 过滤器

Filter 一般用户请求过滤处理，比如全局参数验证、签名验证、登陆验证等等。目前支持三种方式匹配过滤器，类似于 Spring mvc 中的过滤器。

* 后缀名称匹配，比如匹配 `.html` / `.php` 后缀路径
* 路径匹配，比如 `/a/b/\*` ,匹配 `/a/b/` 下所有路径
* 精确完整路径匹配，比如精确匹配 `/user/login`

## 定义 Filter

定义一个 Filter 很简单, 只需基础 `\Swoft\Filter\Filter` 类，实现 `doFilter` 和 `denyFilter` 两个方法。`doFilter` 实现 Filter 处理逻辑，当 Filter 逻辑验证不通过时，调用 `denyFilter`，并返回 `false` 终止执行，反之验证成功，调用 `FilterChain-&gt;doFilter` 执行下一个过滤器。特别说明下，如果需要 Filter 不通过的时候，直接返回用户数据，可以直接使用`Response` 对象实现。

```php
use Swoft\Filter\Filter;

class LoginFilter extends Filter
{
    public function doFilter(Request $request, Response $response, FilterChain $filterChain, int $currentIndex = 0)
    {
        // 逻辑验证
        $uid = $request->getParameter('uid');
        if($uid != 6){
            // 逻辑验证不通过
            $this->denyFilter($request, $response);
            return false;
        }
        return $filterChain->doFilter($request, $response, $filterChain, $currentIndex);
    }

    public function denyFilter(Request $request, Response $response)
    {
        // 直接返回数据给用户
        $response->setResponseContent(json_encode(array('status' => 403, 'msg' => 'need login!')));
        $response->setFormat(Response::FORMAT_JSON);
        $response->send();
    }
}
```

## 配置 Filter

配置一个 Filter 也很简单，只需在 `config/beans/filter.php` 里面配置一个 Filter bean ,并且在 Filter Bean 里面引用即可。`uriPattern` 对应上面说的三种过滤规则。

```php
return [

    // ...
    
    'commonParamsFilter' => [
        'class'      => \App\Beans\Filters\CommonParamsFilter::class,
        // 目录匹配
        'uriPattern' => '/*',
    ],
    'loginFilter'        => [
        'class'      => \App\Beans\Filters\LoginFilter::class,
        // 精确匹配
        'uriPattern' => '/index/login',
    ],
    'filter'             => [
        'filters' => [
            '${commonParamsFilter}',
            '${loginFilter}',
        ],
    ],    
    
    // ...
    
];
```



