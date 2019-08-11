# Using Hint Classes For Better Code Auto-Completion And Navigation #

Arrays and objects of `stdClass` (sometimes called **generic** or **plain** objects) are often used in PHP code as in-memory data structures.  

Due to dynamic nature of PHP language, you can introduce errors too easily by misspelling array keys of object property names.

This article shows how to use hint classes to eliminate possibility of these errors.  

{{ toc }}

## The Problem ##

Consider the following example:

	$input = [
		'label' => 'Username', 
		'comment' => 'Enter your username', 
		'required' => true, 
	];

IDE can't reliably infer possible array keys. When retrieving array item, it can't offer you a list of valid keys, so you have to know available keys spell them correctly:

	// IDE doesn't help with entering array key
	$required = $input['required'];  

IDE doesn't know expected data type of array values, either. When using array item value, you have to know it:

	if ($input['required']) {
		...
	} 
 
The same problem is with plain objects:

	$input = (object)[
		'label' => 'Username', 
		'comment' => 'Enter your username', 
		'required' => true, 
	];

IDE doesn't know anything about `$input` object properties and their types.

## Using Hint Class ##

1. Create hint class:

		<?php

		namespace {module_namespace}\Hints;

		/**
		 * @property string $label
		 * @property string $comment
		 * @property bool $required
		 */
		abstract class InputHint {
		}

2. Add type hint to object variable:

		/* @var InputHint $input */
		$input = (object)[
			'label' => 'Username', 
			'comment' => 'Enter your username', 
			'required' => true, 
		];

## Benefits Of Using Hint Class ##
      
After these steps, IDE gets all the information about object's properties and offers you this list when you type property name. If you accidentally use non-existent property name, IDE will show it to you in another color.

IDE also knows types of the properties and can check if you use property correctly.

Finally, IDE allows to navigation to the definition of the property - to the `InputHint` class. It makes hint class a perfect place to document the properties. 

## What About Arrays? ##

Unfortunately, hint classes doesn't work for arrays. 

Hence, use arrays only where filling the array and using the array is close enough to provide enough information both when you write code and later when you read code.

In more complex scenarios, use plain objects with hint classes. 