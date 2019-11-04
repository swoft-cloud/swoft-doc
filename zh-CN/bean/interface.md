# 接口注入

在实际业务场景中，很多情况需要面向接口编程，需要根据接口类名，注入接口实际实现的对象。面向接口编程可以很方便的解耦业务，如下以发送短信为例，


> 2.0.3 开始支持

## 注解注入

```php
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Annotation\Mapping\Primary;
use Swoft\Bean\BeanFactory;

interface SmsInterface
{
    public function send(string $content): bool;
}


/**
 * Class AliyunSms
 *
 * @since 2.0
 *
 * @Bean()
 * @Primary()
 */
class AliyunSms implements SmsInterface
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return true;
    }
}

/**
 * Class QcloudSms
 *
 * @since 2.0
 *
 * @Bean()
 */
class QcloudSms implements SmsInterface
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return true;
    }
}

/**
 * Class Sms
 *
 * @since 2.0
 *
 * @Bean()
 */
class Sms implements SmsInterface
{
    /**
     * @Inject()
     *
     * @var SmsInterface
     */
    private $smsInterface;

    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return $this->smsInterface->send($content);
    }
}

/* @var SmsInterface $sms*/
$sms = BeanFactory::getBean(Sms::class);

// Send Aliyun sms
$sms->send('sms content');
```

当前是以发送阿里云短信为例，如果业务后续变化，需要使用 Gcloud 送短信，只需移除 `AliyunSms` 的 `@Primary()`注解，把此注解加到 `Qcloud` 类上面，完全解耦业务代码，无任何修改。

> `@Inject()` 注入 bean 名称为空，会优先注入 `@Primary()` 注解标记的实例对象，如果不为空比如 `@Inject(QcloudSms::class)` 就会注入指定的实例对象。

### @Primary

如果一个接口有多个实现，此标记就是制定接口注入时使用的对象，且同一个接口的多个实现类中只能有一个此注解标记，不然启动会提示错误。

## 配置注入

bean 配置 (app\bean.php) 也是支持接口注入的，如上例子把发送短信的 `Sms` 修改下不用注解，其它不变，代码如下：

```php
/**
 * Class Sms
 *
 * @since 2.0
 */
class Sms implements SmsInterface
{
    /**
     * @var SmsInterface
     */
    private $smsInterface;

    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return $this->smsInterface->send($content);
    }
}
```

app\bean.php 文件中配置bean

```php
[
   Sms::class => [
        'smsInterface' => bean(SmsInterface::class)
   ]
]
```

使用代码保存不变，其实现的功能与注解方式是一样的

```php
/* @var SmsInterface $sms*/
$sms = BeanFactory::getBean(Sms::class);

// Send Aliyun sms
$sms->send('sms content');
```
