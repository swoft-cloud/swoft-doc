# 使用实例

``php
$data[] = translate("title", [], 'zh');
$data[] = translate("title", [], 'en');
$data[] = translate("msg.body", ["stelin", 999], 'en');
$data[] = translate("msg.body", ["stelin", 666], 'en');
``

- 第一个参数，若有"."符号，先匹配主题，再匹配key，否则，直接匹配默认主题和key
- 第二个参数，按顺序传入需要替换参数
- 第三个参数，指定翻译语言，如果不指定，会使用默认配置的语言。