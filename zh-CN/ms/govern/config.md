# 配置中心

配置中心主要就是把配置集中化管理，方便统一和维护。本章以 Apollo 为例，从远端配置中心拉取配置以及安全重启服务。如果对 Apollo 不熟悉，可以先看 
Swoft 扩展 Apollo 组件以及阅读 Apollo 官方文档。

## 使用

Swoft 中使用 Apollo 很简单，直接使用一个 console 命令即可：

```php
<?php declare(strict_types=1);


namespace App\Console\Command;

use ReflectionException;
use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Log\Helper\CLog;

/**
 * Class ApolloCommand
 *
 * @since 2.0
 *
 * @Command("apollo")
 */
class ApolloCommand
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @CommandMapping(name="index")
     *
     * @throws ReflectionException
     * @throws ApolloException
     * @throws ContainerException
     */
    public function index(): void
    {
        $namespaces = [
            'application'
        ];

        $this->config->listen($namespaces, [$this, 'updateConfigFile']);
    }

    /**
     * @param array $data
     */
    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);

            CLog::info('Apollo update success！');
            
            // restart
        }
    }
}
```

这里监听了 `application` 命名空间，当配置有改变的时候，回调 `updateConfigFile` 函数，它主要负载更新配置文件，如果服务已启动安全重启。


> 建议不要再去实现，框架启动拉取配置，可以先启动监听命令(包括拉取配置)，然后再启动框架服务即可。这里只是简单的一个使用，开发者可以根据自己的实际业务情况封装。