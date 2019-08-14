# 常见问题

## The HTTP server have been running!(PID: xx) 

出现这种问题，是因为服务已经启动了。通常两种方式解决，第一种方式，stop 服务。第二种方式，`kill pid`。

## Could not scan for classes inside xxx which does not appear to be a file nor a folder

出现这种问题是`composer`源的问题，一般会在`创建项目`(`composer create swoft/swoft swoft`)的时候出现，解决办法很简答只需要切换源`全局设置`即可。如：`composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/`。

更多源的切换请参阅 [Composer配置](../ready/composer.md)

