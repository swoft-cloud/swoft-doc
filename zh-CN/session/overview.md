# Session 会话

## 简介
Session 组件提供 HTTP 服务下的 Session 会话支持，目前仅实现了 Redis 驱动下的 Session 储存支持，由于 Swoft 的设计理念更倾向于分布式和集群，所以我们并不建议使用文件的方式来进行会话储存

## 安装
通过 Composer 安装 `swoft/session` 组件  
1. 在项目 `composer.json` 所在目录执行 `composer require swoft/session`
2. 将 `Swoft\Session\Middleware\SessionMiddleware::class` 中间件加入到全局中间件的配置文件 `config/beans/base.php` 里

```php
'serverDispatcher' => [
    'middlewares' => [
        \Swoft\Session\Middleware\SessionMiddleware::class,
    ]
],
``` 

3. 配置好 `swoft/redis` 组件的连接池配置，此步骤具体可查阅 `swoft/redis` 组件的文档说明

## 依赖
1. PHP 7.0 + 
2. Swoft Framework 1.0 beta +
3. Swoole 2.0.11 +

## 贡献组件代码

1. Session 组件使用 PSR-1, PSR-2, PSR-4 标准;  
2. Session 组件有 PHP 7.0 的最低版本限制，除非有条件地使用该特性，否则 Pull Request 不允许有比 PHP 7.0 更大的PHP版本的语言特性; 
3. 所有的 Pull Request 都必须包含相对于的单元测试，以确保更改能够正常工作。  

## 运行测试

Session 组件使用 PHPUnit 来作为单元测试的支持，可以直接使用 PHPUnit 本身的命令来运行测试，或使用组件提供的 Composer 命令来执行

### 通过 Composer 命令运行测试
`composer test`

### 通过 PHPUnit 命令运行测试
`./vendor/bin/phpunit -c phpunit.xml`
