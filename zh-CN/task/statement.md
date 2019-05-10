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

> 被 `@Task` 标记类的每个方法就是一个任务，如果方法没有使用 `@TestTask` 注解，不会解析成任务。