# 配置视图组件

当你安装了 view 组件后，swoft 将会自动的注册它。

- 为何会自动注册 

:) 请参考： `swoft-view/src/Bootstrap/CoreBean.php`，以及前面的 **组件**

- 视图组件注册到容器里的名称为： `view`
- 基本配置(file: `config/beans/base.php`)

```php
'view' => [
    // class 配置是可以省略的, 因为 view 组件里已经配置了它
    // 'class' => \Swoft\View\Base\View::class,
    'viewsPath' => dirname(__DIR__) . '/resources/views/',
],
```

现在在任何地方都可以通过 `view()` OR `\Swoft::getBean('view')` 来获取组件实例。

## 配置项说明

- `viewsPath` 视图存放路径
- `layout` 默认的布局文件。 调用 `render()` 方法时会默认的使用它
- `suffix` 默认的视图后缀(默认是 `php`)
- `suffixes` 允许的视图后缀列表。 用于判断是否需要添加默认后缀
- `placeholder` 在布局文件里使用的内容占位符。 默认 `{_CONTENT_}`

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
