# 翻译文件格式

不同语言文件，是放在不同的语言文件夹下面。

```
[root@0dd3950e175b languages]# tree 
.
|-- en
|   |-- default.php
|   `-- msg.php
`-- zh
    |-- default.php
    `-- msg.php
```
    
## 翻译文件组成

- 语言，指的是文件夹名称，比如en/zh
- 分类，指的是不同语言文件夹下的文件，多个文件就是多个分类(msg/default)。
- Key，翻译文件内存是按照key/value形式定义
- default.php是默认分类

## 文件内容格式

```php
return [
    'body' => '这是一条消息 [%s] %d',
];
```

- value 替换规则是按照sprintf()函数格式替换

