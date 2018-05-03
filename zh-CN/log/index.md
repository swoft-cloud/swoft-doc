# 日志

日志依赖monolog组件进行扩展，兼容PSR-3规范

## 日志等级

- debug 调试日志
- trace 跟踪日志
- error 错误日志
- info 信息打印日志
- warning 警告日志
- notice 请求日志，每个请求会生成唯一一条notice，里面可以记录整个请求相关的所有信息，方便问题定位。

