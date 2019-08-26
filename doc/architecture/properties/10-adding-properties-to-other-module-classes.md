# Adding Custom Properties To Other Module Classes #

Your module may need to add custom properties to the classes of other modules. 

It helps to separate concerns: without your module installed, that other module won't know anything about your custom property and it will behave as usual. However, once your module is installed, your custom property will be added to the class of that module and it will be treated in the same way as its own properties. 

{{ toc }}

## Adding Custom Properties ##

You can add the property to any object derived from `Osm\Core\Object_` by just assigning it:

	$obj->my_count = 0;

Or by just reading it! If property was not assigned before, it is created and assigned `null`:

	echo $obj->my_count; 
	// null

## Hinting Custom Properties ##

You should add `@property` type hint for every custom property to let the system know about new property, its type and characteristics. 

It is recommended practice, for two reasons:

* In order to save property value in cache when the whole object is cached, you can specify that the property is `@part` (and optionally `@required`).    
* IDE code auto-completion and navigation can only work with type-hinted properties.    

The exact technique of hinting a custom property depends on whether you can edit source code of the class being customized.

### Hinting The Class You Own ###

If you can edit the class being customized, just add `@property` type hint in it and mention in the comment that it actually belongs to other module.

In the following example, `Your_Logger` module adds `shape_id` property to the `Point` class of another module:

	/**
	 * @property float $x @required @part
	 * @property float $y @required @part
	 * 
	 * @see \Your\Logger\Module:
	 *      @property int $shape_id @required @part
	 */
	class Point extends Object_ {
	}

### Hinting 3rd Party Class ###

If you can't edit the class being customized, then: 

1. Create a [hint class](../hint-classes.html) for it in your code base and add `@property` to it:

		<?php
	
		namespace Your\Logger\Hints;
	
		use ThirdParty\Shapes\Point;
	
		/**
		 * @property int $shape_id @required @part
		 */
		abstract class PointHint extends Point {
		}

2. In your code, whenever you use the custom property, declare object variable as an instance of both actual class and hint class:

		/* @var Point|PointHint $point */
		$point = Point::new(['x' => 3.0, 'y' => 5.0, 'shape_id' => 1]);
	
		// IDE knows about `shape_id` property and allows 
		// code completion and navigation to definition
		echo $point->shape_id;

## Adding Lazy Calculation To Custom Property ##

Custom property may be calculated on first access (lazy property):

1. In `@property` hint, add `@default` attribute which means that default value will be lazily assigned to the property: 

		<?php

		namespace Your\Logger\Hints;

		use ThirdParty\Shapes\Point;
	
		/**
		 * @property int $shape_id @required @part @default
		 */
		abstract class PointHint extends Point {
		}

2. In your module class, register a [dynamic trait](../dynamic-traits.html) to standard `Osm\Core\Properties` class:
 
		<?php

		namespace Your\Logger;
	
		use Osm\Core\Modules\BaseModule;
		use Osm\Core\Properties;
	
		class Module extends BaseModule {
		    public $traits = [
		        Properties::class => Traits\PropertiesTrait::class,
		    ];
		}  

3. Create mentioned dynamic trait and add a method named by concatenating the name of the class being customized (`ThirdParty_Shapes_Point`) and the name of the property being added (`shape_id`). The method receives object instance as its single parameter and returns default value:

		<?php
		
		namespace Your\Logger\Traits;
		
		use ThirdParty\Shapes\Point;
		
		trait PropertiesTrait
		{
		    public function ThirdParty_Shapes_Point__shape_id(Point $point) {
				static $count = 0;

		        return ++$count;
		    }
		}
