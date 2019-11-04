# Swoft PostgreSQL

`curtis18/swoft-pgsql` 是对 `PostgreSQL` 在 Swoft 中使用的简单封装，支持连接池配置和某些非连接池的原生配置函数。

## Github

- https://github.com/curtis18/swoft-pgsql.git

## 安装

`curtis18/swoft-pgsql` 需要使用 Swoole 的 `swoole/ext-postgresql` 扩展。而作为 Swoft 的一个额外的扩展组件，需要手动安装：

- 通过 composer 命令:

```bash
composer require curtis18/swoft-pgsql
```

- 通过 `composer.json` 配置:

```json
    "curtis18/swoft-pgsql": "~1.0.1"
```

## 使用

### 基础配置

Swoft 应用的 PostgreSQL 配置都在配置文件 app/bean.php 中。

```php
use Swoft\Pgsql\PgsqlDb;

'pgsql'          => [
	'class'    => PgsqlDb::class,
	'host'     => '127.0.0.1',
	'port'     => 5432,
	'database' => 'dbname',
	'schema'   => ['postgis', 'public'],
	'charset'  => 'utf8',
	'user'     => 'username',
	'password' => 'pass'
],
```

- class 指定当前配置驱动类型
- host 连接地址 默认 `127.0.0.1`
- port 端口 默认 `5432`
- database 连接数据库 默认 `postgres`


### 连接池配置

Swoft 所有连接池配置都差不多，配置都在配置文件 app/bean.php 中，默认的连接池名為 pgsql.pool。

```php
'pgsql.pool'      => [
	'class'     => \Swoft\Pgsql\Pool::class,
	'pgsqlDb'   => bean('pgsql'),
	'minActive' => 2,
	'mixActive' => 20,
	'maxWait'   => 0,
	'maxWaitTime' => 0,
	'maxIdleTime' => 60,
],
```

<p class="tip">
    注意：每一个 worker 都会创建一个同样的连接池。并不是越多越好，参数配置要根据，机器配置和 worker 个数衡量。
</p>


### 使用连接池


```php
<?php declare(strict_types=1);

namespace App\Model\Data;

use Swoft\Pgsql\Pool;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Config\Annotation\Mapping\Config;

/**
 * Class Test
 *
 * @since 2.0
 * 
 * @Bean()
 */
class Test
{
    /**
     * @Inject("pgsql.pool")
     * @var Pool
     */
    private $pgsql;
    
    public function getTest(): array
    {
        $connection = $this->pgsql->createConnection();
        return $connection->select("SELECT * FROM test;");
    }
    
    public function fetchNumTest(): array
    {
        $connection = $this->pgsql->createConnection();
        return $connection->selectFetchNum("SELECT testid, testname FROM test;");
    }
    
    public function bindingTest(int $id = 1, string name = "myname"): array
    {
        $connection = $this->pgsql->createConnection();
        return $connection->select("SELECT * FROM test WHERE testid = $1 AND testname = $2;", array($id, $name));
    }
    
    // 非连接池的原生配置函数
    public function copyFromTest(): bool
    {
        $table = 'test';
        $data = array(
            array('1|C252525A|0|0|02921|02921|2|0|Welcome To Swoft|02921||benny|2019-09-03 14:40:55|1|'),
            array('2|C252525B|0|0|02921|02921|2|0|Welcome To Swoole|02921||curtis|2019-07-03 14:40:55|1|'),
        );

        $connection = $this->pgsql->createConnection();
        $connection->select("TRUNCATE ".$table.";");
	
        return $connection->copyFrom($table, $data);
    }
    
    // 非连接池的原生配置函数
    public function copyToTest(): array
    {
        $table = 'test';
        $connection = $this->pgsql->createConnection();
        return $connection->copyTo($table);
    }
}
```

## 参与贡献

欢迎参与贡献，您可以

- fork 开发仓库 [curtis18/swoft-pgsql](https://github.com/curtis18/swoft-pgsql)
- 修改代码然后发起 PR
- 关于发起PR的[注意事项](https://github.com/swoft-cloud/swoft/issues/829)
