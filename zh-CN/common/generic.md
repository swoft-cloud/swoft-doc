# 通用函数

## env

获取环境变量的值

```php
function env(string $key = null, $default = null): mixed
```

## alias

获取路径别名的值

```php
function alias(string $key): string
```

## bean

从容器中获取bean对象，等同于 `\Swoft::getBean()`

```php
function bean(string $key): object
```

## config

获取应用配置的值

```php
function config(string $key, $default = null): mixed
```

## sgo

开启一个新的协程

```php
function sgo(callable $callable, bool $wait = true): void
```

## server

```php
function server(): \Swoft\Server\Server
```

## context

获取上下文对象

```php
function context(): ContextInterface
```

## container

获取容器对象

```php
function container(): Container
```

## vdump

打印数据，跟 var_dump 一样，只是会在前面多打出调用的位置，方便调试。

```php
function vdump(...$vars)
```
