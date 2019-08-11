# Type Properties #

Contents:

{{ toc }}

## Defining Type Property ##

So far, we considered objects to be of different type if the are of different class, `Circle` or `Rectangle`.

However, it is easier to design complex type hierarchies if type is stored in a property. Often, one class is enough the whole type hierarchy. For example:

	/**
	 * @property string $type @required @part
	 * @property float $radius @required @part
	 * @property float $width @required @part
	 * @property float $height @required @part
	 */
	class Shape extends Object_ {
		const CIRCLE = 'circle';
		const RECTANGLE = 'rectangle';
	}
 
## Using Type Property ##

You can pass object type to the constructor property:

	$shapes = [
		Shape::new(['type' => Shape::CIRCLE, 'radius' => 5.0]), 
		Shape::new(['type' => Shape::RECTANGLE', 'width' => 3.0, 'height' => 2.0]),
	];


## Naming Convention ##

By convention, object's type is stored in `type` property. With the exception for singleton objects - their type is stored in `name` property. 