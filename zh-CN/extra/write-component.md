# 编写组件

swoft 提供了非常方便的自定义和扩展能力，你可以像编写一个普通的 composer 包一样为swoft编写组件。
通过swoft的事件你基本上可以接入swoft应用运行的任何阶段，得到你需要的任何信息。

## 组件骨架结构

这是官方推荐的组件结构，当然你完全可以自定义。但有一点要注意的是：

> Notice: `AutoLoader.php` 是一个组件必须存在的文件，swoft依据它来确定这是一个组件和要扫描哪些目录

```text
├── src/
│   ├── Annotation/  -------- 组件注解类定义(如果你的组件不需要添加新注解，则无需此目录)
│   │   ├── Mapping/  -------- 注解类定义
│   │   └── Parser/   -------- 注解解析类定义
│   ├── Bean/         ------- 一些具有独立功能的 class bean
│   ├── Concern/      ------- traits/abstract classes
│   ├── Contract/     ------- interface classes
│   ├── Exception/
│   ├── Helper/
│   ├── Listener/      ------- 添加事件监听器
│   ├── AutoLoader.php  -------- 组件扫描等信息
├── test/   ------ 单元测试代码目录
│   ├── unit/
│   ├── testing/
│   └── bootstrap.php
├── LICENSE
├── README.md
├── composer.json
└── phpunit.xml
```

## 添加Autoloader

如下，是一个组件的自动加载类：

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

> Notice: 组件的 `Autoloader` 必须继承 `Swoft\SwoftComponent`，才能被swoft正确辨别为组件。

### 方法说明

- `enable(): bool` 是否启用这个组件
- `getPrefixDirs(): array` 这个组件你需要扫描那些目录，你 **完全可以指定只扫描一部分目录**。
- `metadata(): array` 列出组件的基本信息。可以直接通过上面的方式，返回 composer.json 里的信息。
- `beans(): array` 如有需要你可配置添加自定义bean

> Tips: 我们的应用项目 `swoft/swoft` 结构也是类似的，可以看作一个顶级组件，加载配置为 `app/AutoLoader.php`。

## 组件原理

这里介绍一下组件的收集和加载原理，有需要可以做一下了解。

- swoft 通过 composer 的 `ClassLoader` 对象得到所有的 psr4 加载注册信息
- 找到每个psr4命名空间所对应的目录，查看是否有 swoft 需要的 `AutoLoader.php`
  - 允许在启动application时，设置跳过扫描一些确定的命名空间以加快速度。 默认跳过扫描 `'Psr\\', 'PHPUnit\\', 'Symfony\\'` 几个公共的命名空间
- 加载各个组件的 `AutoLoader.php` 文件。通过它的配置 **有目的** 的扫描指定的路径，_避免像 1.0 一样扫描了很多无效的目录_
- `AutoLoader` 必须实现 `LoaderInterface`, 同时可以选择实现其他几个有用的interface
  - 实现 `LoaderInterface` 可以定义要扫描的目录
  - 实现 `DefinitionInterface` 可以定义一个数组来配置一些组件内的bean
  - 实现 `ComponentInterface` 除了同时支持上面的配置外，还可以自定义 **是否启用** 当前组件以及添加一些组件描述
- 开始解析每个组件的 `AutoLoader`，收集注解信息，bean配置等
  - 没有启用的组件，将会跳过解析它的 `AutoLoader::class` 扫描配置
  - 同样允许在启动application时，设置 **禁用** 指定的 `AutoLoader::class` 以达到禁止扫描这个组件的目的

## 代码规范

- 功能描述
- 代码风格遵循 PSR-2
- 遵循 PSR-4 自动加载规范
- 有完善的单元测试

当然，这些只是建议和推荐，更好的代码结构和风格能便于其他开发者阅读使用，利于推广你的组件。
