## 如何使用

使用就很简单了，通过 Swoft 里面的方法可以直接翻译文件，并且可以传递参数和指定翻译语言


```php
\Swoft::t(string $key, array $params, string $locale): string
```

- $key 指定翻译的内容，如果没有 `.` 号，直接默认文件中查找对应 `key`。如果存在 `.` 号，第一段是文件名称，第二段是文件内容里面的key
- $params 传递翻译的参数，数组方式，数组 `key` 对应内容里面的 `{key}`
- $locale 指定翻译的语言，默认是 `en`


## 实例

已配置章节的配置的文件内容和格式为例

```php
\Swoft::t('name', ['name' => 'swoft']); // name swoft
\Swoft::t('name', ['name' => 'swoft'], 'zh'); // 名称 swoft
\Swoft::t('msg.name', ['name' => 'swoft']); // msg name swoft
\Swoft::t('msg.name', ['name' => 'swoft'], 'zh'); // 消息名称 swoft
```