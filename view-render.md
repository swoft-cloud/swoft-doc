# 视图渲染

Swoft 提供了简单方便的视图渲染支持。

## 配置视图渲染组件

```php
'renderer' => [
    'class' => \Swoft\Web\ViewRenderer::class,
    'viewsPath' => dirname(__DIR__) . '/resources/views/',
],
```

现在在任何地方都可以通过 `\Swoft\App::getBean('renderer')` 来获取组件实例。

所有的可配置项：

- `viewsPath` 视图存放路径
- `layout` 默认的布局文件。 调用 `render()` 方法时会默认的使用它
- `suffix` 默认的视图后缀(默认是 `php`)
- `suffixes` 允许的视图后缀列表。 用于判断是否需要添加默认后缀
- `placeholder` 在布局文件里使用的内容占位符。 默认 `{_CONTENT_}`

类文件： `\Swoft\Web\ViewRenderer`

## 使用

### 直接使用

1. 我们添加一个简单的路由来测试使用 renderer

```php
use Swoft\App;
use Swoft\Base\RequestContext;

$router->get('/hello', function() {
  $result = App::getBean('renderer')->renderPartial('hello', ['name' => 'Tom']);
  RequestContext::getResponse()->setResponseContent($result);
});
```

2. 在我们配置的视图目录新建 `hello.php`

oo :) , 简单点 这里就没有写出完整的 html 结构了

```php
// at {project}/resources/views/hello.php

<h2>hello, <?= $name ?></h2>
```

3. 访问 `{host}/hello`

### 在控制器中使用

我们更多的时候都是在控制器里使用的。

> 这里使用注解的方式添加路由和配置视图

```php
/**
 * 控制器demo
 *
 * @AutoController(prefix="/demo2")
 */
class DemoController extends Controller
{
    /**
     * 视图渲染demo - 没有使用布局文件(请访问 /demo2/view)
     * @RequestMapping()
     * @View(template="index/index")
     */
    public function actionView()
    {
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://doc.swoft.org/',
            'doc1' => 'https://swoft-cloud.github.io/swoft-doc/',
            'method' => __METHOD__,
        ];
        // 根据请求适应返回类型，如请求视图，则会根据 @View() 注解来返回视图
        // 这里return的值将会传递给视图
        return $data;
    }


    /**
     * 视图渲染demo - 使用布局文件(请访问 /demo2/layout)
     * @RequestMapping()
     * @View(template="demo/content", layout="layouts/default.php")
     */
    public function actionLayout()
    {
        $layout = 'layouts/default.php';
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://doc.swoft.org/',
            'doc1' => 'https://swoft-cloud.github.io/swoft-doc/',
            'method' => __METHOD__,
            'layoutFile' => $layout
        ];
        return $data;
    }
}
```

> 源码文件： [github](https://github.com/swoft-cloud/swoft/blob/master/app/Controllers/DemoController.php)

说明：

- 若你不添加后缀，会自动追加配置的默认后缀
- 使用相对路径时，将会在我们配置的视图目录里找到对应的view文件
- 使用绝对路径时，将直接使用它来渲染。(支持使用路径别名 `@res/views/my-view.php`)

使用布局文件， 方式有两种：

1. 在配置中 配置默认的布局文件，那么即使不设置 `layout`， 也会使用默认的
2. 如这里一样，手动设置一个布局文件。它的优先级更高（即使有默认的布局文件，也会使用当前传入的替代。）

### 在视图文件里包含其他视图文件

在视图文件里包含其他视图文件，可以使用：

- `include(string $view, array $data, bool $outputIt = true)`
- `fetch(string $view, array $data)`

> 两个方法效果一样。 只是 `fetch()` 需要你手动 echo `<?= $this->fetch('layouts/default/header') ?>`

> 注意： 变量数据有作用域限制。 即是传入视图的变量，无法在包含的视图里直接使用，需要手动通过 `$data` 传入。

```php
<body>
    <?php $this->include('layouts/default/header') ?>

    <div class="container">
        <!-- Content here -->
        <div id="page-content" style="padding: 15px 0;">{_CONTENT_}</div>
        <?php $this->include('layouts/default/footer') ?>
    </div>
</body>
```

视图渲染示例已经包含在源码中，安装启动服务器后 可直接 访问 `{host}/demo2/layout` 查看效果
