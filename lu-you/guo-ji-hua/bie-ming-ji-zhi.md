# 别名机制

通常开发的时候都会遇到使用文件路径，使用的文件路径一般都很多，如果没有统一的管理和维护，就会很混乱。别名就是给一个路径取一个短小好记的名字，其它地方直接使用别名，不需要使用路径。

## 定义别名

```php
// 定义项目根目录别名
App::setAlias('@root', BASE_PATH);

// 如下其它别名也可以根据一个别名来定义
App::setAlias('@app', '@root/app');
App::setAlias('@res', '@root/resources');
App::setAlias('@runtime', '@root/runtime/' . APP_NAME);
App::setAlias('@configs', '@root/config');
App::setAlias('@resources', '@root/resources');
App::setAlias('@beans', '@configs/beans');
App::setAlias('@properties', '@configs/properties');
```

```php
// 批量定义别名
$aliases = [
    '@root'       => BASE_PATH,
    '@app'        => '@root/app',
    '@res'        => '@root/resources',
    '@runtime'    => '@root/runtime',
    '@configs'    => '@root/config',
    '@resources'  => '@root/resources',
    '@beans'      => '@configs/beans',
    '@properties' => '@configs/properties',
    '@commands'   => '@app/Commands'
];
App::setAliases($aliases);
```

## 使用别名

```php
// 获取别名真实路径
$configsPath = App::getAlias('@configs');
```



