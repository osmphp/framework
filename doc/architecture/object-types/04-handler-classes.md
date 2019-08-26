# Handler Classes #

Polymorphic method implementations spread across many class files.

However, having object's type in property, we may factor all the implementations of a polymorphic method into a single class. Such class is called **handler class**.

Using handler class pattern, you can group related logic into a single class, see it all at a glance, refactor common parts into separate helper methods and more.

Handler pattern is recommended for large or open-ended type hierarchies.

Contents:

{{ toc }}

## Creating Handler Class ##

Create a method for each object type (`drawCircle()`, `drawRectangle()`) and a generic public method working for any object type which simply dispatches to type-specific method.

Continuing with the example `Shape` class introduced in article on [type properties](type-properties.html):

	class ShapeRenderer extends Object_ {
		/**
		 * @see \Your\Module\Name\Shape::$type @handler
		 */
		public function draw(Shape $shape) {
			switch ($shape->type) {
				case Shape::CIRCLE: $this->drawCircle($shape); return;
				case Shape::RECTANGLE: $this->drawRectangle($shape); return;
				default: throw new NotSupported();
			}
		}
	
		protected function drawCircle(Shape $shape) {
			// draws a circle on the screen
		}
	
		protected function drawRectangle(Shape $shape) {
			// draws a rectangle on the screen
		}
	} 

Mark the generic method with full type property name and `@handler` attribute as shown above, so that you can easily find all handler classes for given family of objects. 

If additional arguments are needed, consider using [`@temp` properties](../properties/temp-properties.html) for them.

## Using Handler Class ##

Instantiate handler class as a singleton, and use its public method on any `Shape`:

	global $osm_app; /* @var App $osm_app */
	$renderer = $osm_app[ShapeRenderer::class];

	$shapes = [
		Shape::new(['type' => Shape::CIRCLE, 'radius' => 5.0]), 
		Shape::new(['type' => Shape::RECTANGLE', 'width' => 3.0, 'height' => 2.0]),
	];

	foreach ($shapes as $shape) {
		$renderer->draw($shape);
	}

## Multiple Polymorphic Methods ##

If needed, you can create multiple handler classes for the same type hierarchy.

For instance, if you need to convert all shapes to XML:

	class XmlGenerator extends Object_ {
		/**
		 * @see \Your\Module\Name\Shape::$type @handler
		 */
		public function generateXml(Shape $shape) {
			switch ($shape->type) {
				case Shape::CIRCLE: $this->generateCircleXml($shape); return;
				case Shape::RECTANGLE: $this->generateRectangleXml($shape); return;
				default: throw new NotSupported();
			}
		}
	
		protected function generateCircleXml(Shape $shape) {
			return "<circle radius="{$shape->radius}" />";
		}
	
		protected function generateRectangleXml(Shape $shape) {
			return "<rectangle width="{$shape->width}" height="{$shape->height}" />";
		}
	} 
