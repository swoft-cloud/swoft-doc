# 控制器

继承\swoft\web\Controller控制器定义controller，@Inject自动注入Logic依赖，Logic中也可以使用@Inject自动注入dao层或其它依赖，注入的依赖默认是单列的。

```php
class IndexController extends Controller
{
    
    /**
     * @Inject()
     * @var IndexLogic
     */
    private $logic;
    
    
    public function actionDemo()
    {
        // 获取所有GET参数
        $get = $this->get();
        // 获取name参数默认值defaultName
        $name = $this->get('name', 'defaultName');
        // 获取所有POST参数
        $post = $this->post();
        // 获取name参数默认值defaultName
        $name = $this->post('name', 'defaultName');
        // 获取所有参，包括GET或POST
        $request = $this->request();
        // 获取name参数默认值defaultName
        $name = $this->request('name', 'defaultName');
        //json方式显示数据
        //$this->outputJson("data", 'suc');

        // 重定向一个URI
        $this->redirect("/login/index");
    }
}
```



