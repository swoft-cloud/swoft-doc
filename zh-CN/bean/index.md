# 什么是 Bean 

`Swoft` 中一个 `Bean` 就是一个类的一个对象实例。 容器就是一个巨大的工厂，用于存放和管理 `Bean` 生命周期。

在 `Swoft` 中，构成应用程序主干并由`Swoft` IOC容器管理的对象称为`bean`。`bean`是一个由`Swoft` IOC容器实例化、组装和管理的对象。

`IOC`容器可以看成是一个`Beans`关系的集合 

- `bean`是对象，一个或者多个不限定
- `bean`由`Swoft`中一个叫`IOC`的东西管理
- 我们的应用程序由一个个`bean`构成

`BeanFactory`提供了一种先进的配置机制来管理任何种类的bean。

Bean的定义要有`BeanDefinition`描述：当`配置文件`/`注解`被解析后就会在内部转化成一个`BeanDefinition`对象。以后的操作都是对这个对象完成的 

