# 组件入口

每个 swoft 组件都必须有一个 组件的自动加载类 `AutoLoader`

> Notice: 组件的 `Autoloader` 必须继承 `Swoft\SwoftComponent`，才能被swoft正确辨别为组件。

## AutoLoader

```php
<?php declare(strict_types=1);

namespace Your\Component;

use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;
use function dirname;

/**
 * class AutoLoader
 * @since 2.0
 */
final class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component
     *
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * {@inheritDoc}
     */
    public function beans(): array
    {
        return [
            'myBean'    => [
                'class' => MySome::class,
            ],
        ];
    }
}
```

## 方法说明

- `enable(): bool` 是否启用这个组件
- `getPrefixDirs(): array` 这个组件你需要扫描那些目录，你 **完全可以指定只扫描一部分目录**。
- `metadata(): array` 列出组件的基本信息。可以直接通过上面的方式，返回 composer.json 里的信息。
- `beans(): array` 如有需要你可配置添加自定义bean

> Tips: 我们的应用项目 `swoft/swoft` 结构也是类似的，可以看作一个顶级组件，加载配置为 `app/AutoLoader.php`。
