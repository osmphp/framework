# Limitations #

{{ toc }}

## Instantiate Classes Properly ##

Dynamic traits are applied only to objects created using `$m_app->createRaw()` or `Osm\Core\Object_::new()` static method (which internally calls `$m_app->createRaw()` method).

In most cases, `::new()` method is available, so use it to instantiate your classes.

For classes not based on `Object_` class, use `$m_app->createRaw()` method. 

## Ignored Classes ##

You can't apply dynamic traits:

* to most classes in `Osm\Core` namespace, with the exception of `Osm\Core\Properties` class as well as to all module classes. 
 
	Objects of most core classes including descendants of `BaseModule` class are loaded very early, before dynamic trait subsystem is initialized.

* to database migration classes.

	Implementation of dynamic traits only works with class names following PSR-4 convention as defined in `package.json` file. Migration classes do not follow this naming convention. 
