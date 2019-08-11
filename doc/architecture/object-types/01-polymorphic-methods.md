# Polymorphic Methods #

Class method is polymorphic if derived classes implement it in a different way.

Contents:

{{ toc }}

## Defining Polymorphic Method ##

In the following example, all shapes have a single interface - `draw()` method, but shapes of the different type display themselves on the screen in a different way:

	class Shape {
		public function draw() {
			throw new NotSupported();
		}
	}

	class Circle extends Shape {
		public function draw() {
			// draws a circle on the screen
		}
	}

	class Rectangle extends Shape {
		public function draw() {
			// draws a rectangle on the screen
		}
	}

## Using Polymorphic Method ##

You can have an array of different objects and use their common interface in a loop:

	$shapes = [new Circle(), new Rectangle(), new Circle()];

	foreach ($shapes as $shape) {
		$shape->draw();
	}

## Designing Polymorphic Method ##

When designing a polymorphic method in the base class:

* If possible, provide default implementation:

		class Shape {
			public function draw() {
				// draws question icon on the screen
			}
		}

* Otherwise, throw `Manadev\Core\Exceptions\NotSupported` exception to force your application to fail when using the method if derived class doesn't implement it:

		class Shape {
			public function draw() {
				throw new NotSupported();
			}
		}
 
* In rare cases, declare the method and the whole base class as `abstract` to force your application to fail when instantiating a derived class which doesn't implement the method:

		abstract class Shape {
			abstract public function draw();
		}

## Multiple Polymorphic Methods ##

You may have several polymorphic methods in the same base class. 

We recommend, however, to add multiple polymorphic methods to the the base class only if it expects few derived classes. Instead, consider using handler method pattern.  
