# Modules

Modular software development is a well-known practice of dividing your application into several modules, each doing one thing, and doing it well. It increases readability and simplifies maintenance, as application concerns are fully separated from one another, easier to reason about, and to debug.

Modular development also encourages reuse. It's like a puzzle. Using one set of modules, you'll get an e-commerce application, using another set of modules - you'll get a blog application.

Details:

{{ toc }}

### meta.list_text

Modular software development is a well-known practice of dividing your application into several modules, each doing one thing, and doing it well. It increases readability and simplifies maintenance, as application concerns are fully separated from one another, easier to reason about, and to debug.

Modular development also encourages reuse. It's like a puzzle. Using one set of modules, you'll get an e-commerce application, using another set of modules - you'll get a blog application.

## Creating Modules

In Osm Framework, a module is a directory containing a `Module.php` file that defines a class that extends [`BaseModule`](https://github.com/osmphp/core/blob/HEAD/src/BaseModule.php):

    namespace My\Foo;
    ...
    class Module extends BaseModule {
    }

Put the module directory in the project's `src/` directory (more on that [later in this article](#module-discovery)).

## Module Name

Beside the class name, a module also has a name. 

By default, the module name is the class name having `\` replaced with `_`, and `_Module` suffix cut. For example, the name of the `My\Base\Module` module is `My_Base`.

It's also possible to assign name manually. For example, manually assigned name of the `My_Base` module is just `base`:

    namespace My\Base;
    
    use Osm\Core\Attributes\Name;
    ...
    
    #[Name('base')]
    class Module extends BaseModule
    {
        ...
    }

This documentation uses module names and class names interchangeably.

In the codebase, use module names in module-specific theme asset directories:

    ...
    themes/
        _front__tailwind/
            css/
                base/           # assets of the `base` module
                ...             # assets of other modules
            js/
                base/           # assets of the `base` module
                ...             # assets of other modules
            views/
                base/           # assets of the `base` module
                ...             # assets of other modules

We'll cover theme assets in more details later.
    
## Adding Module To Applications (`app_class_name` Property)

In the module file, specify what application the module is a part of:

    use Osm\App\App;
    ...
    class Module extends BaseModule {
        public static ?string $app_class_name = App::class;
        ...
    }

In Osm Framework, an application is a set of modules that you run as a whole. There are several applications defined in every project, each having its own PHP class:

* `Osm\App\App` - the main application that is hosted it on a Web server, and managed using `osm ...` console commands. In most cases, assign the module to this application. 
* `My\Samples\App` - the sample application, a superset of the main application, used in unit tests. Assign the module to this application if it's only going to be used in unit tests.
* `Osm\Tools\App` - the additional application for tooling and code generation, run using `osmt ...` console commands. Assign the module to this application if it adds up to the tooling.

All these classes extends the base application class, [`Osm\Core\App`](https://github.com/osmphp/core/blob/HEAD/src/App.php). Assign the module to the base application if it should be a part of *any* application.

Alternatively, leave the `app_class_name` property unassigned. In this case the module is only used by an application if it's recursively required (more on that in [the next section](#module-dependencies-requires-property)) by another module, assigned to that application. 

## Module Dependencies (`requires` Property)

List the modules, required by your module to function property, for example:

    class Module extends BaseModule {
        ...
        public static array $requires = [
            \My\Base\Module::class,
            \My\Markdown\Module::class,
        ];
        ...
    }

Requiring a module (or, in other words, *declaring a module dependency*) does two things.

First, it guarantees that the required modules are loaded whenever your module is used.

Second, it puts the required modules higher in the array of all modules. 

## Module Order

The application maintains the array of all its modules in the `$osm_app->modules` property. As mentioned [above](#module-dependencies-requires-property), the module array is sorted by module dependency.

The module array is used internally by Osm Framework for merging module-specific settings, running module-specific migrations, building module-specific assets, and other purposes. 

Often, it's important what module merges its settings, or runs its migrations first, and you can manage it using the module's [`requires` property](#module-dependencies-requires-property).  

## Module Discovery

The [Creating Modules](#creating-modules) section suggests putting module directories in the project's `src/` directory. While it's practical advice, it may worth mentioning how modules are discovered. 

Osm Framework checks the `autoload/psr-4` section of the project's `composer.json` file, and of all Composer packages recursively listed in the `require` section of the `composer.json`, and checks every directory listed there. Most often the `autoload/psr-4` section lists the `src/` directory:

    {
        ...
        "require": {
            "osmphp/framework": "^0.12",
            ...
        },
        ...
        "autoload": {
            "psr-4": {
                "My\\": "src/"
            }
        },
        ...
    }

In unit tests, Osm Framework also checks the Composer packages recursively listed in the `require-dev` sections, and the source directories listed in `autoload-dev/psr-4` sections of all the `composer.json` files.

## File Naming Conventions

The only requirement for a module directory is having `Module.php` in it. 

Organize other files using the following naming conventions:

    Advices/        # HTTP advice classes
    Commands/       # console command classes
    Components/     # Blade component classes
        Admin/
        Front/
    Exceptions/     # exception classes
    Hints/          # hint classes
    Migrations/     # migration classes
    Routes/         # HTTP route classes
        Admin/
        Api/
        Front/
    Traits/         # dynamic traits
    functions.php   # helper functions
    Module.php 
    package.json    # required JavaScript packages

## Standard Modules

Out of the box, the project includes the `My_Base` module, and other project modules require it:

    class Module extends BaseModule {
        ...
        public static array $requires = [
            \My\Base\Module::class,
        ];
        ...
    }
 
In turn, the `My_Base` module requires `Osm_Framework_All` module which requires all the standard Osm Framework modules:

    namespace My\Base;
    ...
    class Module extends BaseModule
    {
        ...
        public static array $requires = [
            \Osm\Framework\All\Module::class,
        ];
    }    

It's a sensible default for most projects, but sometimes, you may want to be specific about what standard modules you want to include. 

In this case, replace `Osm_Framework_All` requirement with a fine-grained module list, and only add a standard module if you actually use it, or customize its classes:

    class Module extends BaseModule
    {
        public static array $requires = [
            // add this line if you work with Algolia search indexes
            \Osm\Framework\AlgoliaSearch\Module::class,
            
            // defines `admin`, `api` and `front` areas
            \Osm\Framework\Areas\Module::class,
            
            // add this line if you use Blade templates
            \Osm\Framework\Blade\Module::class,
            
            // add this line if you use cache
            \Osm\Framework\Cache\Module::class,
            
            // add this line if you use `osm ...` console commands
            \Osm\Framework\Console\Module::class,
            
            // add this line if you use relational databases
            \Osm\Framework\Db\Module::class,
            
            // add this line if you work with ElasticSearch indexes
            \Osm\Framework\ElasticSearch\Module::class,
            
            // add this line if you use environment variables
            \Osm\Framework\Env\Module::class,
            
            // add this line if you serve HTTP requests
            \Osm\Framework\Http\Module::class,
            
            // add this line if your pages contain JavaScript
            \Osm\Framework\Js\Module::class,
            
            // instatiates main Laravel objects
            \Osm\Framework\Laravel\Module::class,
            
            // add this line if you log things to files or other places
            \Osm\Framework\Logs\Module::class,
            
            // add this line if you run database or search migrations
            \Osm\Framework\Migrations\Module::class,
            
            // add this line to be able to put the website on maintenance with
            // the `osm http:down` command
            \Osm\Framework\Maintenance\Module::class,
            
            // add this line if you use standard page layout templates, or 
            // render error pages from a Blade template
            \Osm\Framework\Pages\Module::class,
            
            // defines internal application paths
            \Osm\Framework\Paths\Module::class,
            
            // provides a generic interface for working with search indexes
            \Osm\Framework\Search\Module::class,
            
            // add this line if use settings files
            \Osm\Framework\Settings\Module::class,
            
            // add this line if you use multi-theme support
            \Osm\Framework\Themes\Module::class,
            
            // add this line if your projects supports multiple languages
            \Osm\Framework\Translations\Module::class,
        ];
    }
