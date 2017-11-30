# ApplicationContext

应用请求上下文，目前只提供BeanFactory的功能，后续会有新功能提供。

```php
// 类名创建
ApplicationContext::createBean("myBean", MyBean.class);

// 配置创建
$beanConfig = [
    'class' => MyBean.class,
    'pro1' => 'v1',
    'pro2' => 'v2',
];
$constructorArgs = [
    'arg1',
    '${beanName}'
];
ApplicationContext::createBean('myBean', $beanConfig, $constructorArgs);


// 获取一个Bean,没有初始化，从配置中创建
ApplicationContext::getBean("name");

// 某个Bean是否已创建
ApplicationContext::containsBean("name");
```



