# Object Relationships #

Objects are 

This section covers 2 important aspects of object-oriented programming (OOP):

1. distributing responsibilities and defining dependencies among objects
2. organizing objects into data structures and forming object containment hierarchies
 
Contents:

{{ child_pages }}  

The plan:

* object should be responsible for one thing and do it well

#  #

* "use" and "has" relationships

Object's references to o
Object A **uses** object A, if object A, for its whole lifespan, "knows" about object B and uses its properties and/or methods.

Class B is a **dependency** of class A if objects of class B are dependencies of class A.

  

* actually, we don't have dependency injection. 
* Classes know default dependencies
* You may override dependency container 