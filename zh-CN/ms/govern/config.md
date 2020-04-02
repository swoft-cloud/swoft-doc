# 配置中心

配置中心主要就是把配置集中化管理，方便统一和维护。本章以 Apollo 为例，从远端配置中心拉取配置以及安全重启服务。如果对 Apollo 不熟悉，可以先看 
Swoft 扩展 Apollo 组件以及阅读 Apollo 官方文档。

配置中心使用流程

- 编写本地 agent 监听配置的变化，如果有变更，修改本地配置文件
- 重启业务对应的服务

> 本地 agent 一定要比服务先启动，否则服务启动，没法获取最新的配置信息

## 使用

本章以 Swoft 中使用 apollo 为例，当 apollo 配置变更后，重启服务(http-server / rpc-server/ ws-server)。如下是一个 agent 例子：


### 声明 agent

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
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\WebSocket\Server\WebSocketServer;
use Throwable;

/**
 * Class AgentCommand
 *
 * @since 2.0
 *
 * @Command("agent")
 */
class AgentCommand
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @CommandMapping(name="index")
     */
    public function index(): void
    {
        $namespaces = [
            'application'
        ];

        while (true) {
            try {
                $this->config->listen($namespaces, [$this, 'updateConfigFile']);
            } catch (Throwable $e) {
                CLog::error('Config agent fail(%s %s %d)!', $e->getMessage(), $e->getFile(), $e->getLine());
            }
        }
    }

    /**
     * @param array $data
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);

            CLog::info('Apollo update success！');

            /** @var HttpServer $server */
            $server = bean('httpServer');
            $server->restart();

//            /** @var ServiceServer $server */
//            $server = bean('rpcServer');
//            $server->restart();

//            /* @var WebSocketServer $server */
//            $server = bean('wsServer');
//            $server->restart();
        }
    }
}
```

这里声明了一个本地 agent，它监听 apollo 远程配置变更，如果有变更，回调函数，同时返回最新的配置信息，根据业务实际情况重新修改配置文件，再重启相应的服务

### 启动 agent

```
php bin/swoft agent:index
```

> agent 启动，可以后台守护进程启动，防止挂掉

### 启动服务

以启动 Http server 为例

```
/usr/local/php/bin/php /data/www/swoft/bin/swoft http:start -d
```

<p class="tip"> 启动命令的路径，必须是绝对路径 (/data/www/swoft/bin/swoft) 且后台运行模式 (-d)，服务的启动必须在 agent 之后，否则无法获取最新配置信息。本章只是一个简单的使用例子，开发者可以更加自己的实际业务情况，监听 apollo 配置变更，生成配置文件，重启服务</p>