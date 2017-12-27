# 事件进阶了解与使用

### 事件管理器

事件管理器, 也可称之为事件调度器。 事件的注册、调度(触发)都是由它管理的。

```php
use Swoft\Event\EventManager;

$em = new EventManager();
```

## 事件对象

事件对象 - 装载了在触发事件时相关的上下文信息，用户自定义的。

### 预先创建一个事件

- 直接简单的使用类 `Event`

```php
$myEvent = new Event('name', 'target', [ 'some params ...' ]);
```

- 使用继承了 `Event` 的子类

这样你可以追加自定义数据

```php
// create event class
class MessageEvent extends Event
{
    protected $name = 'messageSent';
    
    // append property ... 
    public $message;
}

```

## 事件监听器

监听器允许是: 

1. function 函数
2. 一个闭包
3. 一个监听器类(可以有多种方式)

### 1. function

```php
// ... 

$em->attach(Mailer::EVENT_MESSAGE_SENT, 'my_function');
```

### 2. 闭包

```php
// ... 

$em->attach(Mailer::EVENT_MESSAGE_SENT, function(Event $event) {
    // $message = $event->message;
    // ... some logic
});
```

### 3. 监听器类(有多种方式)

- 类里面存在跟事件相同名称的方法

> 此种方式可以在类里面写多个事件的处理方法

```php
class ExamListener1
{
    public function messageSent(EventInterface $event)
    {
        echo "handle the event {$event->getName()}\n";
    }
}
```

- 一个类(含有 `__invoke` 方法)

> 此时这个类对象就相当于一个闭包

```php
class ExamListener2
{
    public function __invoke(EventInterface $event)
    {
        echo "handle the event {$event->getName()}\n";
    }
}
```

- 实现接口 `EventHandlerInterface`

触发时会自动调用 `handle()` 方法。

```php
class ExamHandler implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        // TODO: Implement handle() method.
    }
}
```

- 实现接口 `EventSubscriberInterface`

可以在一个类里面自定义监听多个事件

```php
/**
 * Class EnumGroupListener
 * @package Swoft\Event\Examples
 */
class EnumGroupListener implements EventSubscriberInterface
{
    const TEST_EVENT = 'test';
    const POST_EVENT = 'post';

    /**
     * 配置事件与对应的处理方法
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            self::TEST_EVENT => 'onTest',
            self::POST_EVENT => ['onPost', ListenerPriority::LOW], // 还可以配置优先级
        ];
    }

    public function onTest(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }

    public function onPost(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }
}
```

## 快速使用

### 绑定事件触发

```php
// a pre-defined event
class MessageEvent extends Event
{
    // append property ... 
    public $message;
}

// in the business
class Mailer
{
    use EventManagerAwareTrait;

    const EVENT_MESSAGE_SENT = 'messageSent';

    public function send($message)
    {
        // ...发送 $message 的逻辑...

        $event = new MessageEvent(self::EVENT_MESSAGE_SENT);
        $event->message = $message;
        
        // 事件触发
        $this->eventManager->trigger($event);
    }
}
```

### 触发事件

```php
$em = new EventManager();

// 绑定事件
$em->attach(Mailer::EVENT_MESSAGE_SENT, 'exam_handler');
$em->attach(Mailer::EVENT_MESSAGE_SENT, function (EventInterface $event)
{
    $pos = __METHOD__;
    echo "handle the event {$event->getName()} on the: $pos\n";
});

// 这里给它设置了更高的优先级
$em->attach(Mailer::EVENT_MESSAGE_SENT, new ExamListener1(), 10);
$em->attach(Mailer::EVENT_MESSAGE_SENT, new ExamListener2());
$em->attach(Mailer::EVENT_MESSAGE_SENT, new ExamHandler());

$mailer = new Mailer();
$mailer->setEventManager($em);

// 执行，将会触发事件
$mailer->send('hello, world!');
```


## 一组事件的监听器

除了一些特殊的事件外，在一个应用中，大多数事件是有关联的，此时我们就可以对事件进行分组，方便识别和管理使用。

- **事件分组**  推荐将相关的事件，在名称设计上进行分组

例如：

```text
// 模型相关：
model.insert
model.update
model.delete

// DB相关：
db.connect
db.disconnect
db.query

// 应用相关：
app.start
app.run
app.stop
```

### 一个简单的示例应用类

```php

/**
 * Class App
 * @package Swoft\Event\Examples
 */
class App
{
    use EventManagerAwareTrait;
    
    const ON_START = 'app.start';
    const ON_STOP = 'app.stop';
    const ON_BEFORE_REQUEST = 'app.beforeRequest';
    const ON_AFTER_REQUEST = 'app.afterRequest';
    
    public function __construct(EventManager $em)
    {
        $this->setEventManager($em);

        $this->eventManager->trigger(new Event(self::ON_START, [
            'key' => 'val'
        ]));
    }

    public function run()
    {
        $sleep = 0;
        $this->eventManager->trigger(self::ON_BEFORE_REQUEST);

        echo 'request handling ';
        while ($sleep <= 3) {
            $sleep++;
            echo '.';
            sleep(1);
        }
        echo "\n";

        $this->eventManager->trigger(self::ON_AFTER_REQUEST);
    }

    public function __destruct()
    {
        $this->eventManager->trigger(new Event(self::ON_STOP, [
            'key1' => 'val1'
        ]));
    }
}
```

### 此应用的监听器类

将每个事件的监听器写一个类，显得有些麻烦。我们可以只写一个类用里面不同的方法来处理不同的事件。

- 方式一： **类里面存在跟事件名称相同的方法**(`app.start` -> `start()`)

这种方式简单快捷，但是限定较死。

```php

/**
 * Class AppListener
 * @package Swoft\Event\Examples
 */
class AppListener
{
    public function start(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }

    public function beforeRequest(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }

    public function afterRequest(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }

    public function stop(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }
}
```

- 方式二：实现接口 `EventSubscriberInterface`

有时候我们并不想将处理方法定义成事件名称一样，想自定义。

此时我们可以实现接口 `EventSubscriberInterface`，通过里面的 `getSubscribedEvents()` 来自定义事件和对应的处理方法

```php
/**
 * Class EnumGroupListener
 * @package Swoft\Event\Examples
 */
class EnumGroupListener implements EventSubscriberInterface
{
    const TEST_EVENT = 'test';
    const POST_EVENT = 'post';

    /**
     * 配置事件与对应的处理方法
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            self::TEST_EVENT => 'onTest',
            self::POST_EVENT => ['onPost', ListenerPriority::LOW], // 还可以配置优先级
        ];
    }

    public function onTest(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }

    public function onPost(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event {$event->getName()} on the: $pos\n";
    }
}

```

### 添加监听

```php
$em = new EventManager();

// register a group listener
$em->attach('app', new AppListener());

// create app
$app = new App($em);

// run.
$app->run();
```

## 事件通配符 `*`

支持使用事件通配符 `*` 对一组相关的事件进行监听, 分两种。

1. `*` 全局的事件通配符。直接对 `*` 添加监听器(`$em->attach('*', 'global_listener')`), 此时所有触发的事件都会被此监听器接收到。
2. `{prefix}.*` 指定分组事件的监听。eg `$em->attach('db.*', 'db_listener')`, 此时所有触发的以 `db.` 为前缀的事件(eg `db.query` `db.connect`)都会被此监听器接收到。

> 当然，你在事件到达监听器前停止了本次事件的传播`$event->stopPropagation(true);`，就不会被后面的监听器接收到了。

示例，在上面的组事件监听器改下，添加一个 `app.*` 的事件监听。

```php
// AppListener 新增一个方法
class AppListener
{
    // ...

    public function allEvent(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event '{$event->getName()}' on the: $pos\n";
    }
}

// ...

$em = new EventManager();

$groupListener = new AppListener();

// register a group listener
$em->attach('app', $groupListener);

// all `app.` prefix events will be handled by `AppListener::allEvent()`
$em->attach('app.*', [$groupListener, 'allEvent']);

// create app
$app = new App($em);

// run.
$app->run();
```
