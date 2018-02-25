# 定义任务

一个类就是一个任务组，类里面的每个方法，就是一个任务。


## 注解

**@Task**

- name 定义任务名称，缺省时类名，用于投递任务，且必须唯一
- coroutine 是否启动一个协程运行业务逻辑，缺省是false，协程支持任务还有bug，正在修复中


## 实例

```php
/**
 * Demo task
 *
 * @Task("demo")
 */
class DemoTask
{
    /**
     * @Reference("user")
     *
     * @var DemoInterface
     */
    private $demoService;

    /**
     * @Inject()
     * @var \App\Models\Logic\UserLogic
     */
    private $logic;

    /**
     * Deliver co task
     *
     * @param string $p1
     * @param string $p2
     *
     * @return string
     */
    public function deliverCo(string $p1, string $p2)
    {
        App::profileStart("co");
        App::trace('trace');
        App::info('info');
        App::pushlog('key', 'stelin');
        App::profileEnd("co");

        return sprintf('deliverCo-%s-%s', $p1, $p2);
    }

    /**
     * Deliver async task
     *
     * @param string $p1
     * @param string $p2
     *
     * @return string
     */
    public function deliverAsync(string $p1, string $p2)
    {
        App::profileStart("co");
        App::trace('trace');
        App::info('info');
        App::pushlog('key', 'stelin');
        App::profileEnd("co");

        return sprintf('deliverCo-%s-%s', $p1, $p2);
    }

    /**
     * Cache task
     *
     * @return string
     */
    public function cache()
    {
        cache()->set('cacheKey', 'cache');

        return cache('cacheKey');
    }

    /**
     * Mysql task
     *
     * @return array
     */
    public function mysql(){
        $result = User::findById(425)->getResult();

        $query = User::findById(426);

        /* @var User $user */
        $user = $query->getResult(User::class);
        return [$result, $user->getName()];
    }

    /**
     * Http task
     *
     * @return mixed
     */
    public function http()
    {
        $client = new Client([
                'base_uri' => 'http://127.0.0.1/index/post?a=b',
                'timeout'  => 2,
            ]);

        $result = $client->post('http://127.0.0.1/index/post?a=b')->getResponse();
        $result2 = $client->get('http://www.baidu.com/');
        $data['result'] = $result;
        $data['result2'] = $result2;
        return $data;
    }

    /**
     * Rpc task
     *
     * @return mixed
     */
    public function rpc()
    {
        return $this->demoService->getUser('6666');
    }

    /**
     * Rpc task
     *
     * @return mixed
     */
    public function rpc2()
    {
        return $this->logic->rpcCall();
    }
}
```

> 任务逻辑里面可以使用 Swoft 所有功能，唯一不一样的是，如果任务不是协程模式运行，所有IO操作，框架底层会自动切换成传统的同步阻塞，但是使用方法是一样的。