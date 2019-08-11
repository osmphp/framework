# Controversial Design Decisions #

Dubysa framework design is based on four priorities:

* **extensibility**: the more one can customize, the better;
* **performance**: the faster, the better;
* **simplicity**: the less files with less lines, the better;
* **consistency**: the more similar solutions are to similar problems, the better. 

As natural as they may seem, these priorities make significant impact on how OOP and SOLID principles are applied in Dubysa framework.

Throughout this article we use the notion of "user code". It means any code of a 3rd party developer using the code of original developer. 

Contents:

{{ toc }}

## Encapsulation ##

> Encapsulation is used to refer to one of two related but distinct notions:
>
> * A language mechanism for restricting direct access to some of the object's components.
> * A language construct that facilitates the bundling of data with the methods (or other functions) operating on that data.

The main advantage of encapsulation is reducing error rate in user code.

### Lazy Properties ###

[Lazy property pattern](lazy-properties.html) violates encapsulation principle by advocating use of public properties. 

The main advantages of lazy property pattern over standard approach (property getter method with lazy calculation logic): 

* it is easy to introduce custom properties into any object with a hint class
* code is simpler
* it works faster 

The main drawback is that although it encourages user code to only read property values of the object but never write them (except `@temp` properties), it doesn't enforce read-only use. User code can change the value of the property hence potentially breaking the code.

To the defence of this decision, standard PHP allows the same violation with `ReflectionProperty::setAccessible()`. 

In future versions, this issue will be mitigated by static code analysis tool.

### Dynamic Traits ###

Using [dynamic trait](dynamic-traits.html), user code can introduce public methods into almost any class. 

The main advantages of dynamic traits are unprecedented extensibility of standard code and separation of concerns. 

The main drawback is that injected public methods may allow access to otherwise protected properties and methods. The framework allows that, but leaves the responsibility on user code. 

### Handler Pattern ###

[Handler pattern](handler-pattern.html) encourages separating "data objects" from "logic objects".

The main advantage of handler pattern is more maintainable code for complex class hierarchies.

The main drawback is that data objects expose data for use of logic objects via public properties, and they could be left `protected` if data and logic would be in one class.

The drawback doesn't much damage as most properties are public anyway due to lazy property pattern and in the future possible errors will be mitigated with a tool.

## Inheritance ##

> Inheritance is the mechanism of basing a class upon another class, retaining similar implementation.

Originally is initially meant for code reuse. However, standard industry practice advices to prefer composition over inheritance for code reuse.

Dubysa framework follows standard industry practice. In simple case - one base class and several derived classes - inheritance is used. In more complex cases, composition is used, that is, reusable code is in a class which is referenced by user class via property and its public methods are used.   

## Polymorphism ##

> Polymorphism is the provision of a single interface to entities of different types.

Dubysa extensively uses polymorphism, especially in [registry pattern](registry-pattern.html): standard code defines base class with a single interface, and both standard and user code register different implementation classes in configuration files.  
 
## Single Responsibility Principle ##

> A class should only have a single responsibility, that is, only changes to one part of the software's specification should be able to affect the specification of the class.

This principle is applied without exception.

## Open-Closed Principle ##

> Software entities should be open for extension, but closed for modification.

### Dynamic Traits ###

Dynamic traits allow not only adding methods to almost any class (which is OK with open-closed principle), but also replacing existing method with custom ones. 

This in theory can break user code which expects behaving standard method in certain way. 

In practice, developer of a dynamic trait, responsible for not breaking user code, is interested in doing so. 

## Liskov Substitution Principle ##

> Objects in a program should be replaceable with instances of their subtypes without altering the correctness of that program.

This principle is applied without exception.

## Interface Segregation Principle ##

> Many client-specific interfaces are better than one general-purpose interface. Also known as design by contract.

Main advantage of this principle is hiding implementation details from user code, so that user code doesn't know anything about actually working classes and is only aware about public interfaces.

Our practice has shown that separating interfaces from implementation often does more harm than good:

* code tends to be more complex.
* interfaces often leak implementation details anyway.  

As a simpler alternative to programming to contract, Dubysa encourages programming to base class.

## Dependency Inversion Principle ##

> One should "depend upon abstractions, [not] concretions.

This principle is applied without exception.

## "Service Locator Is Anti-Pattern" ##

In some literature, service locator is not advised.

Still, Dubysa uses service locator pattern for [lazy dependency injection](lazy-dependency-injection.html). 

Minor drawback is that dependencies are resolves in run-time, not in compile-time.

However, major advantage is that drastically simplifies dependency injection.   

## Global Variables ##

Common wisdom says avoiding usage of global variables.

However, Dubysa introduces 3 global variables (`$m_app`, `$m_classes` and `$m_profiler`) as it considerably increases performance (up to 30%). 

Common drawback of global variables is that code is hard to test. On the contrary, Dubysa code is fully testable.  