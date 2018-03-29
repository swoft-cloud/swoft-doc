# 使用视图

- 通过方法： `view()` 渲染一个视图文件
- 通过 `\Swoft::getBean('renderer')->rander('view file')` 渲染一个视图文件
- 在控制器的action注释上还可以快捷的使用 `@View()` 来使用

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
     * @View(template="index/index") - 将会渲染 `resources/views/index/index.php` 文件
     */
    public function actionView()
    {
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://doc.swoft.org/',
            'method' => __METHOD__,
        ];

        // 根据请求适应返回类型，如请求视图，则会根据 @View() 注解来返回视图
        // 这里return的值将会传递给视图
        return $data;
    }

    /**
     * 视图渲染demo - 使用布局文件(请访问 /demo/layout)
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

## 如何查找视图

- 若你不添加后缀，会自动追加配置的默认后缀
- 使用相对路径时，将会在我们配置的视图目录里找到对应的view文件
- 使用绝对路径时，将直接使用它来渲染。(支持使用路径别名 `@res/views/my-view.php`)

## 使用布局文件

使用布局文件，方式有两种：

1. 在配置中 配置默认的布局文件，那么即使不设置 `layout`，也会使用默认的(视图的可用配置请看上一节)
2. 如示例一样，可以手动设置一个布局文件。它的优先级更高（即使有默认的布局文件，也会使用当前传入的替代。）
3. 你可以传入 `layout=false` 来禁用渲染布局文件
