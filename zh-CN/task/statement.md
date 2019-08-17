# 声明任务

使用任务前，必须定义任务，定义任务很简单。如下定一个任务：

```php
/**
 * Class TestTask
 *
 * @since 2.0
 *
 * @Task(name="testTask")
 */
class TestTask
{
    /**
     * @TaskMapping(name="list")
     *
     * @param int    $id
     * @param string $default
     *
     * @return array
     */
    public function getList(int $id, string $default = 'def'): array
    {
        return [
            'list'    => [1, 3, 3],
            'id'      => $id,
            'default' => $default
        ];
    }

    /**
     * @TaskMapping()
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($id > 10) {
            return true;
        }

        return false;
    }
}    
```

## @Task

标记类是一个任务

- name 指定任务名称，默认全路径类名


## @TaskMapping

映射名称

- name 名称映射，默认就类的方法名称

> ##### 注意
> 1. 被 `@Task` 标记类的每个方法就是一个任务，如果方法没有使用 `@TaskMapping` 注解，不会解析成任务。
> 2. Task投递前，会经过 `Swoft\Task\Packet::pack()` 方法被json_encode，因此，投递entity时，task获得的是entity的数组。同时，投递无法被json_encode的参数会导致报错
（如果是异步任务且没有开启额外的日志，可能效果是task直接结束而没有报错信息）。