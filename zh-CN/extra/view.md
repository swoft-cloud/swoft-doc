# 视图渲染

Swoft 官方提供了一个简单的视图渲染组件. 使用php原生语法，提供基本的布局，内部引入文件等功能。

> github - https://github.com/swoft-cloud/swoft-view

## 安装

视图渲染作为一个额外的独立组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require swoft/view
```

- 通过 composer.json 配置:

```json
    "swoft/view": "~2.0.0"
```

# 配置视图组件

当你安装了 view 组件后，swoft 将会自动的注册它。

- 视图组件注册到容器里的名称为： `view`
- bean配置(file: `app/beans.php`)

```php
'view' => [
    // class 配置是可以省略的, 因为 view 组件里已经配置了它
    // 'class' => \Swoft\View\Base\View::class,
    'viewsPath' => dirname(__DIR__) . '/resource/views/',
],
```

现在在任何地方都可以通过 `view()` OR `\Swoft::getBean('view')` 来获取组件实例。

## 配置项说明

- `viewsPath` 视图存放路径
- `layout` 默认的布局文件。 调用 `render()` 方法时会默认的使用它
- `suffix` 默认的视图后缀(默认是 `php`)
- `suffixes` 允许的视图后缀列表。 用于判断是否需要添加默认后缀
- `placeholder` 在布局文件里使用的内容占位符。 默认 `{_CONTENT_}`

## 使用视图

- 通过方法： `view()` 渲染一个视图文件
- 通过 `\Swoft::getBean('view')->rander('view file')` 渲染一个视图文件
- 在控制器的action注释上还可以快捷的使用 `@View()` 来使用(_2.0 暂未支持_)

## 使用示例

```php
/**
 * 控制器demo
 *
 * @Controller(prefix="/demo")
 */
class DemoController
{
    /**
     * 视图渲染demo - 没有使用布局文件(请访问 /demo/view)
     * @RequestMapping()
     */
    public function view()
    {
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://swoft.org/docs',
            'method' => __METHOD__,
        ];

        // 将会渲染 `resource/views/site/index.php` 文件
        return view('site/index', $data);
    }

    /**
     * 视图渲染demo - 使用布局文件(请访问 /demo/layout)
     *
     * @RequestMapping()
     */
    public function layout()
    {
        $layout = 'layouts/default.php';
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://swoft.org/docs',
            'method' => __METHOD__,
            'layoutFile' => $layout
        ];

        return view('site/content', $data, $layout);
    }
}
```

## 如何查找视图

- 若你不添加后缀，会自动追加配置的默认后缀
- 使用相对路径时，将会在我们配置的视图目录里找到对应的view文件
- 使用绝对路径时，将直接使用它来渲染。(支持使用路径别名 `@res/views/my-view.php`)

## 使用布局文件

使用布局文件，方式有两种：

1. 在配置中 配置默认的布局文件，那么即使不设置 `layout`，也会使用默认的(视图的可用配置请看上一节)
2. 如示例一样，可以手动设置一个布局文件。它的优先级更高（即使有默认的布局文件，也会使用当前传入的替代。）
3. 你可以传入 `layout=false` 来禁用渲染布局文件

## 视图加载静态文件

Swoft 可以提供静态资源访问的支持，将静态文件放置于根目录下的 `public` 目录内即可，下面是一个引用的示例

> 注意 需要配置静态文件处理，引用时无需包含 `public`

```html
<script type="text/javascript" src="/static/some.js"></script>
```

## 引入其他视图文件

在视图文件里包含其他视图文件，可以使用：

- `include(string $view, array $data, bool $outputIt = true)`
- `fetch(string $view, array $data)`

> 两个方法的区别是 `fetch()` 需要你手动调用 echo `<?= $this->fetch('layouts/default/header') ?>`

**注意：**

> 变量数据有作用域限制。 即是传入视图的变量，无法在包含的视图里直接使用，需要通过第二个参数 `$data` 传入到子级视图

```php
<body>
    <?php $this->include('layouts/default/header', ['logo' => 'xx/yy/logo.jpg']) ?>

    <div class="container">
        <!-- Content here -->
        <div id="page-content" style="padding: 15px 0;">{_CONTENT_}</div>
        <?php $this->include('layouts/default/footer') ?>
    </div>
</body>
```