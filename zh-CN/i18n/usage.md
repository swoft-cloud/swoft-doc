### 3.实用案例
语言转换仅仅至于要调用一个简单的方法,
下面是方法描述:

``` php
/**
 * @param string $key       文本数组中,对应文本键值
 * @param array  $params    注入到文本中的参数,以关联数组的形式
                            [
                                'param1' => 'str1',
                                'param2' => 'str2'
                            ]

 * @param string $locale    资源文件夹下, 分组文件名称.
 */
\Swoft::t(string $key, array $params, string $locale): string
```

实例演示:
``` php
<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * Test 用演示实例
 * 
 * @since 1.0.0
 * 
 * @Controller(perfix="test")
 */
class TestController
{

    // ... 

    /**
     * i18n 示例
     * 
     * @RequestMapping(route="i18n", method={RequestMethod::GET})
     *
     * @return array
     */
    public function i18n(Request $request):array
    {
        // 显示 language/en/default.php 文本模板:
        // 'sayhello' => 'Hey {name}!',
        $res['en'] = \Swoft::t('sayhello', ['name' => 'man']);

        // 更换模板可以使用 '.' 
        // 显示 language/en/msg.php 模板
        $res['en-msg'] = \Swoft::t('msg.sayhello', ['name' => 'man'], 'en');

        // 显示 language/zh/default.php 模板
        $res['zh'] = \Swoft::t('sayhello', ['name' => '李华'], 'zh');

        // 显示 language/zh/msg.php 模板
        $res['zh-msg'] = \Swoft::t('msg.sayhello', ['name' => '李华'], 'zh');

        return $res;
    }

    // ... 
}

```
        
结果输出:

``` json
{
    "en": "Hey man!",
    "en-msg": "Wath's up! man",
    "zh": "早上好,李华",
    "zh-msg": "晚上好,李华"
}
```