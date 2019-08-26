# Modules

Osm module is a directory which contains PHP, JavaScript, CSS and other files 
related to specific functionality.

Benefits of modular programming - dividing monolithic application into relatively simple modules a- are well known. Individual modules are easier to understand, maintain, update and customize.

## Modules And Composer Packages ##

* about application modules (project-specific) and vendor package modules (reusable).
* link to understanding and creating osmphp packages
 
## Creating new Module ##

## Specifying Module Dependencies ##

* how hard and soft dependencies differ
* direct and indirect dependencies
* practical hints
	* if your module introduces new layers, make sure it depends on `Osm_Framework_Layers`
	* ...

## Order In Which Modules Are Loaded ##

* less dependent are loaded before more dependent
* very important, it affects various subsystems:
	* configuration is merged starting from less dependent modules
	* layers are merged starting from less dependent modules
	* migrations are run starting from less dependent modules

## Understanding Module Directory Structure ##

Typical module directory structure:

	config
		settings.php
	migrations
	...

Explain which directory is for what.	

##  ##
Osm module and directory structure is created according `psr-4` specification for autoloading classes from file paths.


Classes
-----------------

# Names
Short class name is a noun. 

Full class name including namespace and short name should be unique within a project.

## Class `Object_`
Each module class should extend `Object_` class directly or indirectly corresponding to a class which extends `Object_` class. 

Directly:

		class Module extends Object_ {
			...
		}

Example of indirect extent:

		class Module extends BaseModule {
			...
		}
		
## Class and path naming standards
Module files are stored in own directory which is dedicated only for the files of the module. 

There are two types of modules: 

  - project specific modules, located in `app/src` directory,
  - reusable modules, used in more than one project, stored in `vendor/osmphp` directory. 

Every class in the module should be stored in own file and name of the class should be exactly the same as name of the file.
Keep in mind that class and file names should be case sensitive equivalent.  
For example, `app/src/Hello/Dummy.php` corresponds to the class `Dummy` defined inside this file.

Every module should have `Module.php` file in module directory. 

## Namespaces
Module should have own namespace which is unique within a project. `namespace` is a basic PHP convention pseudo directory structure. When it is quite many classes then at some time you can find that name for new class names becomes too long or it is already used by other class.  
Dependence between module directories and namespaces are stored in Composer.json. 

For example `autoload` block:

    "autoload": {
        "psr-4": {
            "App\\": "app/src/",
            "App\\Tests\\": "app/tests/",
            "App\\Samples\\": "app/samples/"
        }

shows that in `app/src/` directory classes with `App` namespace are stored.
Subdirectory `app/src/hello` corresponds to `App\Hello` namespace class. 

## Module dependencies
Modules can use another modules. 

Can be situation when module A cannot work without module B. This is called `dependency`. 

Dependency can be soft and hard. If one module cannot work without another module, then it is hard dependency.

If one module should be executes before another - this is soft dependency.

## How to create module?

## How to reuse module?

## Directory structure

It is required to name files and directories exactly like it is 
common for Osm including capitalization of letters.

By default **Studly Caps** notation is used for directory names.

In special cases directory names are written in **Snake case** notation.

### Module `config` directory 


Module configuration should be stored in `config` folder. So let's create `app/src/Docs/config`.

Inside configuration directory we should create `translations` subdirectory where we should store text and message translations for supported locales and languages.

[More about handling translations in Osm can be found here](../development-basics/translations)


### Module `Controllers` directory ##

PHP classes processing HTTP requests historically are named **Controllers**.

In Osm all possible http requests are divided into several areas. 
One is callsed web for visual requests 
In Osm own controllers is created for each `area` processed by application.

`Area` in Osm is an application presentation layer, 
for example `frontend` providing content to system end-user 
or `backend` which is used by website administrator. 

Another example of `Area` is API when users communicates with application by HTTP requests without user interface.

If application has only one presentation area it used to have `web` name In Osm. 

Controllers for each area should be stored in `Controllers` directory. 

If application is processing too many requests and it requires several controllers for the area, subdirectory can be created for each area controllers. 

