# 容器

swoft 基于 PSR-11 规范设计了自己容器，并基于注解增强了它的功能。

**容器** 就是一个巨大的工厂，用于存放和管理 `Bean` 生命周期。

## 什么是 Bean 

**Bean** 在`Swoft` 中就是一个类的一个对象实例的统称。

在 `Swoft` 中，构成应用程序主干并由`Swoft` IOC容器管理的对象称为`bean`。`bean`是一个由`Swoft` IOC容器实例化、组装和管理的对象。

`IOC`容器可以看成是一个`Beans`关系的集合 

- `bean`是对象，一个或者多个不限定
- `bean`由`Swoft`中一个叫`IOC`的东西管理
- 我们的应用程序由一个个`bean`构成

`BeanFactory`提供了一种先进的配置机制来管理任何种类的bean。

Bean的定义要有`BeanDefinition`描述：当`配置文件`/`注解`被解析后就会在内部转化成一个`BeanDefinition`对象。以后的操作都是对这个对象完成的 

## 哪些是Bean

<p class="tip">
  Bean 不等于 `@Bean`，虽然大多数情况下指的是同一个东西。
</p>  

如下的，所有类注解标记的class，放到容器里都可以称之为Bean对象。

类注解，例如：

- `@Bean` 最常用的bean注解
- `@Listener`
- `@Controller`
- `@Command`
- `@WsModule`
- `@WsController`
- 等等...
