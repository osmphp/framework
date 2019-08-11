# Creating Hint Class #

By convention, hint classes are created in `Hints` subnamespace of your module, have `Hint` suffix and are declared `abstract`.

Hint classes doesn't define any properties or methods in class body. Instead, hint classes declare properties and methods in class comment:

Example:

	<?php

	namespace {module_namespace}\Hints;

	/**
	 * @property float $x
	 * @property float $y
	 * @method string toXml(bool $prettyPrint = false)
	 */
	abstract class PointHint {
	}
