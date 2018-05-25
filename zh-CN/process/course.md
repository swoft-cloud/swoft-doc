# 定义进程

定义进程主要是通过 `@Process` 注解完成定义。

## 注解

**@Process**   
 
 拥有属性：
 
 - `name` 进程唯一标识，默认类名
 - `boot` 是否为Server前置进程，默认false
 - `inout` 参数，参考 swoole [$redirect_stdin_stdout](https://wiki.swoole.com/wiki/page/214.html)，默认 `false`
 - `pipe` 参数，参考 swoole [$create_pipe](https://wiki.swoole.com/wiki/page/214.html)，默认 `true`
 - `coroutine` 是否启动一个协程来运行进程逻辑即开启协程模式，默认false
 - `num` 启动进程的个数，默认是 `1`
 
## 创建进程类
 
 无论是Server前置进程或用户自定义进程，定义方式都是一样，实现 `ProcessInterface` 接口，需要实现run和check方法
 
 - run 方法里面实现进程的主要**业务逻辑**
 - check 方法用于验证进程启动的前置条件，返回true启动进程，返回false不启动进程。
 
### Server 前置进程

- 随着server启动，将会附加到 server, 并被其管理
- 保证 `boot=true` 和 `check()` 返回 `true` 才会启动

```php
 /**
  * Boot process
  *
  * @Process(boot=true)
  */
 class MyProcess implements ProcessInterface
 {
     public function run(SwoftProcess $process)
     {
         $pname = App::$server->getPname();
         $processName = "$pname myProcess process";
         $process->name($processName);
 
         echo "Custom boot process \n";
 
         $result  = Task::deliverByProcess('sync', 'deliverCo', ['p', 'p2']);
         var_dump($result);
 
         // 在此自启动进程中，
         // 我们手动的创建了另一个自定义进程
         // 实际使用中是无需这样做的，在这个方法里我们已经可以写需要的逻辑了
         ProcessBuilder::create('customProcess')->start();
     }
 
     public function check(): bool
     {
         return true;
     }
 }
```

### 用户自定义进程
 
 - 不会随着server启动
 - 可以使用 `ProcessBuilder::create('customProcess')->start()` 来创建它
 - 如何启动使用
   - 如上面的，在一个自启动进程里来启动它。 :) 当然，这其实是没必要的
   - 监听server的相关事件，在里面启动它
   - 命令行里，执行某个命令时。启动使用它
   - ... 在你需要的时候
 
```php
  /**
   * Custom process
   *
   * @Process(name="customProcess", coroutine=true)
   */
  class MyProcess implements ProcessInterface
  {
      public function run(SwoftProcess $process)
      {
          $pname = App::$server->getPname();
          $processName = "$pname myProcess process";
          $process->name($processName);
  
          echo "Custom child process \n";
          var_dump(Coroutine::id());
      }
  
      public function check(): bool
      {
          return true;
      }
  }
```

<div class="alert alert-warning" role="alert">
<strong>提示!</strong>
run 方法里面可以使用 Swoft 所有功能。
唯一不一样的是，如果进程不是协程模式运行，所有IO操作，框架底层会自动切换成传统的同步阻塞，但是使用方法是一样的
</div>

## 运行进程

运行进程很简单，是通过进程唯一标识名称运行。

```php
ProcessBuilder::create('customProcess')->start();
```

> 前置进程会被swoft主动调用并运行

## 投递任务

若进程里面需要投递任务功能，需要依赖 `swoft/task` 组件。
组件安装成功后，直接调用方法投递，具体如何投递任务Task里面有详细介绍。

```php
$result  = Task::deliverByProcess('sync', 'deliverCo', ['p', 'p2']);
var_dump($result);
```
