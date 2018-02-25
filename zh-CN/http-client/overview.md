# HTTP 客户端

框架提供统一抽象的 HttpClient 来实现 HTTP 调用，客户端会自动根据当前环境在*协程异步客户端驱动*和*同步阻塞客户端驱动*中作出选择

## 安装
通过 Composer 安装 `swoft/http-client` 组件即可，  
在项目根目录执行 `composer require swoft/http-client`

## 依赖
1. PHP 7.0 + 
2. Swoft Framework beta +
3. Swoole 2.0.11 +
4. cURL 扩展 (用于同步阻塞的请求，在非协程环境下会自动退化为 cURL 驱动)

## 贡献组件代码

HttpClient 使用 PSR-1, PSR-2, PSR-4 和 PSR-7 标准;  
HttpClient 应该是精简和快速的，并且尽可能的减少依赖，这意味着并不是每个 Pull Request 都会被接受;  
HttpClient 有 PHP 7.0 的最低版本限制，除非有条件地使用该特性，否则 Pull Request 不允许有比 PHP 7.0 更大的PHP版本的语言特性;  
HttpClient 将以协程客户端为主，仅在非协程环境下才去使用其它驱动，若存在协程客户端暂时无法实现的特性，必须存在条件让用户来启用;  
所有的 Pull Request 都必须包含相对于的单元测试，以确保更改能够正常工作。  

## 运行测试

HttpClient 使用 PHPUnit 来作为单元测试的支持，可以直接使用 PHPUnit 本身的命令来运行测试，或使用组件提供的 Composer 命令来执行

### 通过 Composer 命令运行测试
`composer test`

### 通过 PHPUnit 命令运行测试
`./vendor/bin/phpunit -c phpunit.xml`