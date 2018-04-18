# Session

## 安装并启用
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
3. 配置好 `swoft/redis` 组件的连接池配置，此步骤具体可查阅 `swoft/redis` 组件的文档说明， `Redis` 驱动将会直接通过 `@Inject()` 注解注入 `\Swoft\Redis\Redis` 类用于操作

## 配置 Session 
通过对 `SessionManager` bean 的配置实现对 `Session` 配置的变更，若 `Session` 配置不存在，对 `./config/beans/` 内任意一个配置文件添加以下配置即可
```php
// 注意Bean大小写
'sessionManager' => [
    'class' => \Swoft\Session\SessionManager::class,
    'config' => [
        'driver' => 'redis',
        'name' => 'SWOFT_SESSION_ID',
        'lifetime' => 1800,
        'expire_on_close' => false,
        'encrypt' => false,
        'storage' => '@runtime/sessions',
    ],
],
```

### Config 配置项
- `driver`    
类型：`enum`，可选值 (`redis|file`)  
Session 驱动，目前仅支持将 `redis` 驱动用于生产环境，`file` 驱动暂未实现 GC(垃圾回收)

- `name`
类型：`string`  
Session 键名，对应到用户端储存 Session 信息的 `Cookies` 的键名

- `lifetime`
类型：`int`  
Session 生命时长，单位为 `秒`

- `expire_on_close`
类型：`boolean`  
在结束请求时立刻使当前 `Session` 过期

- `encrypt`
类型：`boolean`  
是否加密储存 `Session` 内容

- `storage`
类型：`int`  
Session 信息储存位置，仅在 `file` 驱动下生效

### 常见问题

Q: 每次的请求都产生了不同的 SessionID  
A: 检查 Nginx 配置是否在反向代理时有将 `Host` Header 设置到反向代理的请求去，通常在设置反向代理的时候都会带上下列的 Header 项，以确保反向代理后获取到的信息是一致的  

```bash
proxy_set_header Host $host;
proxy_set_header X-Real-IP $remote_addr;
proxy_set_header REMOTE-HOST $remote_addr;
proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
```

其中 `proxy_set_header Host $host;` 就是将当前的域名也设置到反向代理的请求的 Header 上，请检查是否有设置，以确保 Cookies 上设置的 Host 是与当前访问域名是一致的
