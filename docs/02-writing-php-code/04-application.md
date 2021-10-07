# Application

In Osm Framework, an application is a set of modules that you run as a whole. There are several applications defined in the project, each having its own PHP class. Mostly, deal with the main one, `Osm\App\App`. Beside the class name, every application also has a name, the main one is named `Osm_App`.

Access the current application object, and the main parts of Osm Framework, via the global `$osm_app` object and its properties. Add your own long-living objects there. Run an application using its HTTP or console entry point, or using `Apps::run()`. 

Details:

{{ toc }}

#### meta.abstract

In Osm Framework, an application is a set of modules that you run as a whole. There are several applications defined in the project, each having its own PHP class. Mostly, deal with the main one, `Osm\App\App`. Beside the class name, every application also has a name, the main one is named `Osm_App`.

Access the current application object, and the main parts of Osm Framework, via the global `$osm_app` object and its properties. Add your own long-living objects there. Run an application using its HTTP or console entry point, or using `Apps::run()`.

## Application Class

In Osm Framework, an application is a set of modules that you run as a whole. 

There are several applications defined in every project, each having its own PHP class derived from [`Osm\Core\App`](https://github.com/osmphp/core/blob/HEAD/src/App.php) class. All modules registered with the `Osm\Core\App` class become a part of any application.   

The main application, [`Osm\App\App`](https://github.com/osmphp/framework/blob/HEAD/app/App.php), handles HTTP requests, and the commands issued using the `osm` command line alias. This application implements all the user interface, and all the data handling logic. Most modules are parts of the main application.  

The sample application, [`My\Samples\App`](https://github.com/osmphp/project/blob/HEAD/samples/App.php), extends the main application, and, hence, inherits all the modules that are parts of the main application. In addition, the sample application may contain additional modules that define additional tables or classes used exclusively in unit tests. This application is only run in unit tests.

The tool application, [`Osm\Tools\App`](https://github.com/osmphp/framework/blob/HEAD/tools/App.php), handles the commands issued using the `osmt` command line alias. This application implements useful utilities used for maintaining the project, and for code generation.

Finally, the project application, [`Osm\Project\App`](https://github.com/osmphp/core/blob/HEAD/project/App.php), is used for introspecting all modules and classes of the project regardless to which application they belong. It's only used for reflection, invoking user code in context of this application may produce unexpected results.  

In most cases, you'll deal with the main application class, `Osm\App\App`. However, in order to run your code in the context of any application, define application variables using the base class, `Osm\Core\App`.

## Application Name

Beside the class name, every application also has a name:

* `Osm\App\App` is named `Osm_App`;
* `My\Samples\App` is named `My_Samples`;
* `Osm\Tools\App` is named `Osm_Tools`;
* `Osm\Project\App` is named `Osm_Project`.

In general, the application name is the class name having `\` replaced with `_`, and `_App` suffix cut. 

Use the application name for compiling the application:

    osmc Osm_App
    
Provide the application name in the `gulpfile.js` configuration:

    ...
    global.config = {
        'Osm_Tools': [],
        'Osm_Project': [],
        'Osm_App': ['_front__tailwind', '_front__my']
    };
    ...

Internally, the application name is used for naming the application-specific directories under the `temp` and `public` of the project.

## `$osm_app` - Current Application

When Osm Framework runs an application, it instantiates the application class, and put the application object into the global `$osm_app` variable. Access the current application object as follows:

    use Osm\Core\App;
    ...
    // get the reference to the current application object
    global $osm_app; /* @var App $osm_app */  
    ...
    // use the application projecties
    $osm_app->logs->default->notice('Hello, world!');

## Application Object Hierarchy

The main parts of Osm Framework are accessible via the global `$osm_app` variable:

* `$osm_app->base_urls` - base URLs of the application areas
* `$osm_app->console` - console command runner
* `$osm_app->cache` - application cache storage
* `$osm_app->class_name` - [application class name](#application-class)
* `$osm_app->classes` - detailed class, property and method information gathered from all the modules that are part of the application
* `$osm_app->db` - application relational database 
* `$osm_app->descendants` - cacheable class inheritance crawler
* `$osm_app->http` - HTTP request handler
* `$osm_app->logs` - [application logs](08-framework-logging.md)
* `$osm_app->modules` - [modules](22-framework-modules.md) that are part of the application. Every module is referenced by its class name, for example, `$osm_app->modules[\My\Base\Module::class]`. 
* `$osm_app->name` - [application name](#application-name)
* `$osm_app->packages` - Composer packages contributing modules to the application
* `$osm_app->paths` - application file paths
* `$osm_app->search` - [application search indexes](../05/27-framework-search.md)
* `$osm_app->settings` - application settings
* `$osm_app->theme` - current visual theme of the application

## Application Object As A Container

The `$osm_app` application object is a container for all the long-living objects of the application. A long-living object is either a property of the application object itself, or it's a property of one of the application child objects.  

The term "long-living object" means an object that lives all the time an HTTP request, or a console command is handled. Its counterpart, a short-living object, is typically created, executed, and released in a single operation.  

Make your long-living objects as properties of your module object. If the long-living object is globally useful by nature, use a [dynamic trait](21-framework-dynamic-traits.md) for making it a property of the application object.

## Application Entry Points

The main application, `Osm_App` has two entry points: one handle HTTP requests, the other - console commands.

In both cases, Osm Framework creates an application object, and then executes some code in the context of that application.

### HTTP Entry Point

The HTTP entry point is located in the `public/Osm_App/index.php` file. It's executed every time the application received an HTTP request:

    <?php
    
    declare(strict_types=1);
    
    use Osm\App\App;
    use Osm\Runtime\Apps;
    use function Osm\handle_errors;
    
    require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
    umask(0);
    handle_errors();
    
    Apps::$project_path = dirname(dirname(__DIR__));
    Apps::run(Apps::create(App::class), function (App $app) {
        $app->handleHttpRequest()->send();
    });

### Console Entry Point

The console entry point is located in the `vendor/osmphp/framework/bin/console.php` file. It's executed every time a console command is executed using the `osm` command-line alias:

    <?php
    
    declare(strict_types=1);
    
    use Osm\Runtime\Apps;
    use Osm\App\App;
    use function Osm\handle_errors;
    
    require 'vendor/autoload.php';
    umask(0);
    handle_errors();
    
    Apps::$project_path = getcwd();
    Apps::run(Apps::create(App::class), function (App $app) {
        $app->console->run();
    });

## Running Applications

While one application is running, you may execute another application using similar code. For example, reflect over all the modules installed within a project as follows:

    use Osm\Project\App;
    ...
    
    $result = [];
    
    Apps::run(Apps::create(App::class), function (App $app) use (&$result) {
        // use $app->modules and $app->classes to introspect 
        // all modules and classes of the projects and its 
        // installed dependencies
        
        // put the introspection data you need into `$result`. Don't reference
        // objects of the `Osm_Project` application, as they'll won't work 
        // outside this closure. Instead, create plain PHP objects and arrays, 
        // and fill them as needed 
    });

Due to technical limitations, while running the child application, don't use objects created in the parent application context. Instead, use a temporary variable to pass the results of child application execution to the parent application, as shown above. 
 