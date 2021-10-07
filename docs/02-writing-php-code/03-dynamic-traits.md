# Dynamic Traits

Using dynamic traits, customize anything. Inject your code in the beginning, in the end, or instead of any standard method. Even more, add new properties and methods to the existing standard classes.

Details:

{{ toc }}

### meta.abstract

Using dynamic traits, customize anything. Inject your code in the beginning, in the end, or instead of any standard method. Even more, add new properties and methods to the existing standard classes.

## Examples

Let's begin with some examples.

### Custom 404 Page

The default implementation of the 404 page displays `Page not found` message in plain text: 

![Plain Text 404 Page](plain-text-404-page.png)

It's implemented in the [`Responses`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Responses.php) class of the `Osm_Framework_Http` module: 

    class Responses extends Object_ {
        ...
        public function notFound(string $message): Response {
            return $this->plain($message, 404);
        }
        ...
    }

Users of a real-world application expect something more visual, and the [`ResponsesTrait`](https://github.com/osmphp/framework/blob/HEAD/src/Pages/Traits/ResponsesTrait.php) of the `std-pages` module renders 404 page using a Blade template:

    #[UseIn(Responses::class)]
    trait ResponsesTrait
    {
        protected function around_notFound(callable $proceed, string $message)
            : Response
        {
            /* @var Responses $this */
    
            if (!$this->error_page_theme?->views->exists('std-pages::404')) {
                return $proceed($message);
            }
    
            try {
                return $this->errorView('std-pages::404',
                    ['message' => $message], status: 404);
            }
            catch (\Exception $e) {
                $this->log(
                    __("Can't render :status page: ",
                        ['status' => 404]) .
                    "{$e->getMessage()}\n\n{$e->getTraceAsString()}");
    
                return $proceed($message);
            }
        }
        ...
    }

Here is the result:

![404 Page Rendered From A Template](404-page-rendered-from-template.png)

The `ResponsesTrait::around_notFound()` method is called *instead of* `Responses::notFound()` method. In addition, Osm Framework passes the `$proceed` parameter referencing the original method, and the `ResponsesTrait::around_notFound()` invokes it if it can't render the page from a template:

    return $proceed($message); 

### Custom Logger

Some classes are designed to be extended using dynamic traits. 

For example, all the application logs are defined as properties of the [`Logs`](https://github.com/osmphp/framework/blob/HEAD/src/Logs/Logs.php) class. In case you need your own logger, add a computed property and its getter to this class:

    /**
     * @property Logger $my
     */
    #[UseIn(Logs::class)] 
    trait LogsTrait
    {
        protected function get_my(): Logger {
            global $osm_app; /* @var App $osm_app */
    
            $logger = new Logger('my');
            $logger->pushHandler(new RotatingFileHandler(
                "{$osm_app->paths->temp}/logs/my.log"));
    
            return $logger;
        }
    } 

Then, add log entries using new `$osm_app->logs->my` property.

For more details about logging, see [Logging](08-framework-logging.md).

Other good candidates for custom properties:

* [`App`](https://github.com/osmphp/core/blob/HEAD/src/App.php) - the global `$osm_app` object.
* [`Settings`](https://github.com/osmphp/framework/blob/HEAD/src/Settings/Hints/Settings.php) - the application settings, `$osm_app->settings`. It's a hint class, and therefore you don't have to define property getters. 
* [`Logs`](https://github.com/osmphp/framework/blob/HEAD/src/Logs/Logs.php) - the application logs, `$osm_app->logs`.

## Defining Dynamic Trait

Let's say, there is a `Foo` class that you want to extend with a dynamic trait:

    class Foo extends Object_ {
        public function exists(string $code): bool {
            ...
        } 
    }

Define the trait in the `Traits` sub-namespace:

    <?php
    ...    
    namespace My\Base\Traits;
    
    use Osm\Core\Attributes\UseIn;
    use My\Base\Foo;
    
    #[UseIn(Foo::class)]
    trait FooTrait {
    }

## Adding Properties And Methods

Add computed properties and methods as usual. 

In order to prevent name collisions, add some unique prefix to the property and method names. In the example below, the prefix is `my_`:

    /**
     * @property string $my_property
     */
    #[UseIn(Foo::class)]
    trait FooTrait {
        protected function get_my_property(): string {
            ...
        }
        
        public function my_method(): void {
            ...
        }
    }

## Overriding Existing Methods

Override an existing method by defining a method with the same name and the `around_` prefix. Add the additional `callable $proceed` parameter to the original signature:

    #[UseIn(Foo::class)]
    trait FooTrait {
        protected function around_exists(callable $proceed, string $code): bool {
            // this is executed before the original method
            ...
            $result = $proceed($code);
            ...
            // this is executed after original method
            return $result;
        }
    }

In case the trait code doesn't use the original method parameters, replace them with `...$args`, both in the method signature, and in the `$proceed` call:

    #[UseIn(Foo::class)]
    trait FooTrait {
        protected function around_exists(callable $proceed, ...$args): bool {
            // this is executed before the original method
            ...
            $result = $proceed(...$args);
            ...
            // this is executed after original method
            return $result;
        }
    }

## Stacking Multiple Dynamic Traits

Multiple modules can add their dynamic traits to the same class. In this case new properties and methods are added from all the traits, while existing method overrides are *stacked* based on module order. 

Let's illustrate stacking with an example. Module `Y` requires module `X`, and both `X` and `Y` modules override the `Foo::bar()` method:

    namespace X\Traits;

    #[UseIn(Foo::class)]
    trait FooTrait {
        protected function around_bar(callable $proceed): void {
            $proceed();
        }
    } 
    ...
    namespace Y\Traits;

    #[UseIn(Foo::class)]
    trait FooTrait {
        protected function around_bar(callable $proceed): void {
            $proceed();
        }
    } 

When the `bar()` method is called, `Y\Traits\FooTrait::around_bar()` is executed first, as the `Y` module is the most dependent one.

Then the `Y` module calls `$proceed()`, and this line executes the `X\Traits\FooTrait::around_bar()` method.

Finally, `X` module calls `$proceed()`, and the original `bar()` method is executed.

## How It Works

You may wonder how this magic happens, and what is the trick. 

Normally, you develop the project with `gulp watch` running in the background, and it *compiles* the application whenever code changes.

For every original class, the compiler generates a class that extends the original class, uses all the registered dynamic traits, and stacks the `around_...()` method calls.

When you instantiate the original class with the `Foo::new()`, Osm Framework actually instantiates the generated class instead.