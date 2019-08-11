# Advising Existing Methods #

In this article, we will modify existing method `draw()` in the following class:

    <?php
    
    namespace ThirdParty\Shapes;

    class Point extends Object_ {
		public function draw(Surface $surface) {
			...
		}
    }

Method modification is called an **advice**, and the process of modifying a method is sometimes called **advising the method**. 

Contents:

{{ toc }}

## Creating And Registering Method Advice ##

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
		    protected function around_draw(callable $proceed, Surface $surface) {
				// add logic to be executed before original method here
	
				// call original method
				$result = $proceed($surface); 
	
				// add logic to be executed after original method here
	
				return $result;
		    }
		}

> **Note**. You may use the same technique to advise `protected` methods. 

## Understanding Advices ##

At runtime, when `draw()` method is called, the system actually calls `around_draw()` advice, passing it special `$proceed` parameter followed by all the arguments which caller code passed to `draw()` method.

Simply put, advice is executed instead of original method. However, typical advice calls original method using `$proceed($surface)` syntax, so advice is said to be executed **around** the original method. That's why advices come with `around_` prefix.    

## Using Variable Argument List Syntax ##

If the advice doesn't need to process method parameters, you may use [variable argument list syntax](https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list):

    protected function around_draw(callable $proceed, ...$args) {
		// add logic to be executed before original method here

		// call original method
		$result = $proceed(...$args); 

		// add logic to be executed after original method here

		return $result;
    }

Even if list of `draw()` parameter changes, with `...$args` syntax your advice code will still work!

## Applying Several Advices To The Same Method ##

Several modules (let's say modules `A`, `B` and `C`) can advise the same method. 

In this case the system sorts all the advices from the least dependent module to the most dependent module. In our example, if `A` module depends on `C` and `C` module depends on `B` module, sorted list would consist of advice from module `B`, advice from module `C` and advice from module `A`.

At runtime, when `draw()` method is called, the system will call the last advice in the list, in our example, advice from module `A`.

When advice from module `A` calls the `$proceed` callback, the system will call previous advice in the list, in our example, advice from module `C`. In the same manner, when advice from module `C` calls the `$proceed` callback, advice from module `B` will be called.  

Finally, when advice from module `B` calls the `$proceed` callback, it will call the original `draw()` method as this advice is first in the list.

## Advanced Advices ##

Beside obvious possibility to add some logic before and after the method call, you can also:

1. Modify method arguments. You are not forced to pass method arguments as is, you can replace them with your own values before calling the original method.
2. Modify method result. You can replace value returned by original method with your own after calling the original method.
3. In some cases call original method and in other cases executed your own logic **instead** of the original method. 
4. Completely replace the original method with your own by not calling `$proceed` callback.

> **Caution**. Avoid completely replacing original methods as it can break advices of other modules which can be not executed at all. 