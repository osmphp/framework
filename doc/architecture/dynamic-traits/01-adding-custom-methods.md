# Adding Custom Methods #

In this article, we will add new method to the following class:

    <?php
    
    namespace ThirdParty\Shapes;

    class Point extends Object_ {
    }

Contents:

{{ toc }}

## Creating And Registering Custom Method ##

1. In your module class, register new trait to be "weaved" into the class you want to customize:
 
		<?php

		namespace Your\Logger;
	
		use Manadev\Core\Modules\BaseModule;
		use ThirdParty\Shapes\Point; 

		class Module extends BaseModule {
		    public $traits = [
		        Point::class => Traits\PointTrait::class,
		    ];
		}  

2. Create mentioned dynamic trait and define new method in it:

		<?php
		
		namespace Your\Logger\Traits;
		
		trait PointTrait
		{
		    public function log() {
				echo 'point logged';
		    }
		}

## Hinting Custom Methods ##

You should add `@method` hint for every custom method to let the system know about new method, its return type and accepted parameters. 

It is recommended practice, as `@method` hint feeds IDE with vital information for its code auto-completion and navigation features.    

The exact technique of hinting a custom method depends on whether you can edit source code of the class being customized.

### Hinting The Class You Own ###

If you can edit the class being customized, just add `@method` type hint in it and mention in the comment that it actually belongs to other module:

In the following example, `Your_Logger` module adds `shape_id` property to the `Point` class of another module:

    <?php
    
    namespace ThirdParty\Shapes;

	/**
	 * @see \Your\Logger\Module:
	 *      @method void log()
	 */
    class Point extends Object_ {
    }

### Hinting 3rd Party Class ###

If you can't edit the class being customized, then create a [hint class](../hint-classes.html) for it in your code base and add `@property` to it:

	<?php

	namespace Your\Logger\Hints;

	use ThirdParty\Shapes\Point;

	/**
	 * @method void log()
	 */
	abstract class PointHint extends Point {
	}

In your code, whenever you use the custom method, declare object variable as an instance of both actual class and hint class:

	/* @var Point|PointHint $point */
	$point = Point::new();

	// IDE knows about `log` method and allows 
	// code completion and navigation to definition
	$point->log();
