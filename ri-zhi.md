# 日志

日志记录一般用户问题的问的分析，系统的定位。目前日志规划有debug trace error info warning notice等级别。每种不同的级别用户记录不同重要程度的信息。系统会为每一个请求生成一条notice,并且一个请求产生的所有日志都有一个相同的logid,notice里面记录该请求的详细信息，比如uri 总共耗时 缓存或db操作时间等等信息。  
使用很简单，如下：

```
// 标记开始
App::profileStart("tag");

App::error("this errro log");
App::info("this errro log");
App::debug("this errro log");

// 标记结束
App::profileEnd("tag");

// 统计缓存命中率
App::counting("cache", 1, 10);
```

输出日志格式如下:

```python
2017/08/05 11:17:02 [error] [swoft] [logid:5985a92e27519] [spanid:0] trace[IndexController.php:123,app\controllers\IndexController->actionLog] this errro log
2017/08/05 11:17:02 [info] [swoft] [logid:5985a92e27519] [spanid:0] trace[IndexController.php:124,app\controllers\IndexController->actionLog] this errro log
2017/08/05 11:17:02 [debug] [swoft] [logid:5985a92e27519] [spanid:0] trace[IndexController.php:125,app\controllers\IndexController->actionLog] this errro log
2017/08/05 11:17:02 [notice] [swoft] [logid:5985a92e27519] [spanid:0] [9(ms)] [2(MB)] [/index/log] [] profile[app.route.match=0.61(ms)/1,app.route=0.62(ms)/1,tag=3.21(ms)/1] counting[cache=1/10]
```




