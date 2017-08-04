# ApplicationContext

应用请求上下文，目前只提供BeanFactory的功能，后续会有新功能提供。



```php

class ApplicationContext
{
    /**
     * 运行过程中创建一个Bean
     *
     * Below are some examples:
     *
     * // 类名称创建
     * ApplicationContext::createBean('name', '\swoft\web\UrlManage');
     *
     * // 配置信息创建，切支持properties.php配置引用和bean引用
     * ApplicationContext::createBean(
     *  [
     *      'class' => '\swoft\web\UrlManage',
     *      'field' => 'value1',
     *      'field2' => 'value'2
     *  ]
     * );
     *
     * @param string       $beanName the name of bean
     * @param array|string $type     class definition
     * @param array        $params   constructor parameters
     *
     * @return mixed
     */
    public static function createBean($beanName, $type, $params = [])
    {
        // ...
    }

    /**
     * 查询一个bean
     *
     * @param string $name bean名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        // ...
    }

    /**
     * bean是否存在
     *
     * @param string $name Bean名称
     *
     * @return bool
     */
    public static function containsBean($name)
    {
        // ...
    }

}
```

