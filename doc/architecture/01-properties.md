# Properties #

`Manadev\Core\Object_` class allows:

* assigning property values when creating an object
* calculating property value only once and only when actually accessed
* specifying that property must be assigned prior first use
* specifying that property should be serialized if object is serialized

Most classes inherit from `Manadev\Core\Object_`, and listed property features are widely used in Dubysa modules, so it is important to understand how `Object_` properties work and most important, why.

> **Important**. Treat all public properties as read only unless documentation explicitly says that the property is writable.
 
Contents:

{{ child_pages }}
