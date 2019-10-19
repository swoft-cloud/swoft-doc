# 数据展示

Console数据展示 - 提供格式化信息的输出显示。

主要功能封装在命名空间 `Swoft\Console\Advanced\Formatter` 下，提供了 `Swoft\Console\Helper\Show` 辅助类来快速使用它们。

## 标题文本输出

使用 `Show::title()/$output->title()`

```php
public static function title(string $title, array $opts = [])
```

## 段落式文本输出

使用 `Show::section()/$output->section()`

```php
public static function section(string $title, string|array $body, array $opts = [])
```

## 列表数据展示输出 

```php
public static function aList(array $data, string $title, array $opts = [])
```

- `$data` array 列表数据。可以是key-value 形式，也可以只有 value，还可以两种混合。
- `$title` string 列表标题。可选的
- `$opts` array 选项设置(**同表格、面板的选项**)
    - `leftChar` 左侧边框字符。默认两个空格，也可以是其他字符(eg: `*` `.`)
    - `keyStyle` 当key-value 形式时，渲染 key 的颜色风格。 默认 `info`, 设为空即是不加颜色渲染
    - `titleStyle` 标题的颜色风格。 默认 `comment`

> `aList` 的默认选项，可以渲染一个命令的帮助信息。

使用 `Show::aList()/$output->aList()`

```php
$title = 'list title';
$data = [
     'name'  => 'value text', // key-value
     'name2' => 'value text 2',
     'more info please XXX', // only value
];
Show::aList($data, $title);
```

渲染效果：

![fmt-list](https://raw.githubusercontent.com/inhere/php-console/master/docs/screenshots/fmt-list.png)

## 多列表数据展示输出

```php
public static function mList(array $data, array $opts = [])
```

> `mList` 的默认选项，可以渲染一组命令的帮助信息。效果与 `helpPanel()` 相同，并且自定义性更高。


使用 `Show::mList()/$output->mList()` 别名方法 `Show::multiList()`

```php
$data = [
  'list1 title' => [
     'name' => 'value text',
     'name2' => 'value text 2',
  ],
  'list2 title' => [
     'name' => 'value text',
     'name2' => 'value text 2',
  ],
  // ... ...
];

Show::mList($data);
```

渲染效果：

![fmt-multi-list](https://raw.githubusercontent.com/inhere/php-console/master/docs/screenshots/fmt-multi-list.png)

## 面板展示信息输出

```php
public static function show(mixed $data, string $title = 'Information Panel', array $opts = [])
```

展示信息面板。比如 命令行应用 开始运行时需要显示一些 版本信息，环境信息等等。

使用 `Show::panel()/$output->panel()`

```php
$data = [
    'application version' => '1.2.0',
    'system version' => '5.2.3',
    'see help' => 'please use php bin/app -h',
    'a only value message',
];
Show::panel($data, 'panel show', ['borderChar' => '#']);
```

渲染效果：

![fmt-panel](https://raw.githubusercontent.com/inhere/php-console/master/docs/screenshots/fmt-panel.png)

## 数据表格信息输出

```php
public static function table(array $data, $title = 'Data Table', array $opts = [])
```

使用 `Show::table()/$output->table()`

- 可直接渲染从数据库拉取的数据(会自动提取字段名作为表头)

```php
// like from database query's data.
$data = [
 [ col1 => value1, col2 => value2, col3 => value3, ... ], // first row
 [ col1 => value4, col2 => value5, col3 => value6, ... ], // second row
 ... ...
];

Show::table($data, 'a table');
```

- 自己构造数据时，还要写字段名就有些麻烦了。可以通过选项配置 `$opts` 手动配置表头字段列表

```php
// use custom head
$data = [
 [ value1, value2, value3, ... ], // first row
 [ value4, value5, value6, ... ], // second row
 // ... ...
];

$opts = [
  'showBorder' => true,
  'columns' => [col1, col2, col3, ...]
];
Show::table($data, 'a table', $opts);
```

渲染效果预览：

![table-show](https://raw.githubusercontent.com/inhere/php-console/master/docs/screenshots/table-show.png)

## 渲染帮助信息面板 

```php
public static function helpPanel(array $config, $showAfterQuit = true)
```

使用 `Show::helpPanel()/$output->helpPanel()`

```php
Show::helpPanel([
    "description" => 'a help panel description text. (help panel show)',
    "usage" => 'a usage text',
    "arguments" => [
        'arg1' => 'arg1 description',
        'arg2' => 'arg2 description',
    ],
    "options" => [
        '--opt1' => 'a long option',
        '-s' => 'a short option',
        '-d' => 'Run the server on daemon.(default: <comment>false</comment>)',
        '-h, --help' => 'Display this help message'
    ],
], false);
```

渲染效果预览：

![alt text](https://raw.githubusercontent.com/inhere/php-console/master/docs/screenshots/fmt-help-panel.png "Title")
