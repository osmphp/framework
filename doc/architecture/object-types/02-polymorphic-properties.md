# Polymorphic Properties #

Class property is polymorphic if it is calculated on first access ("lazy") and if derived classes calculate it in a different way. 

Contents:

{{ toc }}

## Defining Polymorphic Property ##

In the following example, all shapes have `area` property, but shapes of the different type calculate it in a different way:

	/**
	 * @property float $area @required
	 */
	class Shape extends Object_ {
	}

	/**
	 * @property float $radius @required @part
	 */
	class Circle extends Shape {
	    protected function default($property) {
	        switch ($property) {
	            case 'area': return 2.0 * pi() * $this->radius;
	        }
	        return parent::default($property);
	    }
	}

	/**
	 * @property float $width @required @part
	 * @property float $height @required @part
	 */
	class Rectangle extends Shape {
	    protected function default($property) {
	        switch ($property) {
	            case 'area': return $this->width * $this->height;
	        }
	        return parent::default($property);
	    }
	}

## Using Polymorphic Property ##

You can have an array of different objects and use their common interface in a loop:

	$shapes = [
		Circle::new(['radius' => 5.0]), 
		Rectangle::new(['width' => 3.0, 'height' => 2.0]),
	];

	$area = 0.0;
	foreach ($shapes as $shape) {
		$area += $shape->area;
	}

## Designing Polymorphic Property ##

When designing a polymorphic property in the base class:

* If possible, provide default implementation:

		/**
		 * @property float $area @required
		 */
		class Shape extends Object_ {
		    protected function default($property) {
		        switch ($property) {
		            case 'area': return 0.0;
		        }
		        return parent::default($property);
		    }
		}

* Otherwise, mark property as `@required` to force your application to fail when using the property if derived class doesn't implement it:

		/**
		 * @property float $area @required
		 */
		class Shape extends Object_ {
		}
 
