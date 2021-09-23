<p align="center">
    <a href="https://github.com/osmphp/framework/actions"><img src="https://github.com/osmphp/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/osmphp/framework"><img src="https://img.shields.io/packagist/dt/osmphp/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/osmphp/framework"><img src="https://img.shields.io/packagist/v/osmphp/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/osmphp/framework"><img src="https://img.shields.io/packagist/l/osmphp/framework" alt="License"></a>
</p>

Osm Framework is an open-source, insanely fast, unprecedentedly extensible, and
fun to work with PHP 8 framework for creating modern Web applications. It's
built on top of tried and tested Symfony and Laravel components.

# Documentation 

* Getting Started
    * [Introduction](https://osm.software/blog/21/05/framework-introduction.html)
    * [Installation](https://osm.software/blog/21/08/framework-installation.html)
    * [Command Line Aliases](https://osm.software/blog/21/08/framework-command-line-aliases.html)  
    * Directory Structure
    * Using Gulp
* Writing PHP Code
    * [Computed Properties](https://osm.software/blog/21/09/framework-computed-properties.html)
    * [Modules](https://osm.software/blog/21/09/framework-modules.html)
    * [Dynamic Traits](https://osm.software/blog/21/09/framework-dynamic-traits.html)
    * [Application](https://osm.software/blog/21/09/framework-application.html)
    * Hint Classes
    * Reflection
    * Testing
    * PHPStorm
* Creating Web Applications
    * Requests
    * Routes
    * Areas
    * Advices
    * Responses
    * Views And Components
    * Themes
    * Assets
* Creating Console Applications 
* Processing Data
    * Migrations
    * Database 
    * [Search](https://osm.software/blog/21/05/framework-search.html)
* Writing JavaScript Code
    * Classes
    * Controllers
    * Testing 
* Other Features
    * [Logging](https://osm.software/blog/21/09/framework-logging.html)
    * Caching
    * Configuration
    * Translations
    * Maintenance Mode
    * Production Mode
    * Helper Functions
    * Extending Gulp Scripts
* [License](https://github.com/osmphp/framework/blob/HEAD/LICENSE)

# Top Features

## Extensibility (Dynamic Traits) 

Let's examine the extensibility bit in more detail. 

Imagine using some e-commerce software that processes new sales order as follows:

    class Order extends Object_ {
        public function submit(): void {
            $this->validate();
            $this->applyDiscounts();
            $this->applyTaxes();
            $this->save();
        }
        
        protected function validate(): void {
            // standard validation logic
            ...
        }
        
        ...
    }

If you need to customize this logic, for example, check if all the purchased items are in stock, you can add it after the validation phase:

    trait OrderTrait {
        protected function around_validate(callable $proceed): void {
            // first, execute the standard validation
            $proceed();
            
            // then, add the logic that checks the stock
            ...
        }
    } 

Under the hood, Osm Framework applies this PHP trait dynamically to `Order` class, and overrides standard `validate()` method with your custom `around_validate()` method.

This way, you can customize any method, in almost any class.

You can introduce new properties and methods to existing classes, too:  

    /**
     * @property bool @are_all_items_in_stock   
     */
    trait OrderTrait {
        protected function checkStockItem(): bool {
            ...
        } 
    } 

## Fast And Test-Friendly Computed Properties

In Osm Framework, a **computed (or "lazy") property** is a public property of a PHP class that is computed once on first access using matching `get_` method.  

For example, consider a class that reads and transforms a Markdown file into HTML:

    /**
     * @property string $path Relative file path in the `data` directory. 
     *      Provide this property in the constructor.
     * @property string $absolute_path Absolute file path
     *
     * @property string $text Original text in Markdown format
     * @property string $html Text converted to HTML
     */
    class MarkdownFile extends Object_ {
        protected function get_absolute_path(): string {
            // get the reference to the global application object which,
            // among other things, stores the absolute path of the `data`
            // directory in its `paths->data` property 
            global $osm_app; /* @var App $osm_app */
    
            return "{$osm_app->paths->data}/posts/{$this->path}";
        }
    
        protected function get_text(): string {
            return file_get_contents($this->absolute_path);
        }

        protected function get_html(): ?string {
            // convert the text into HTML using `michelf/php-markdown` 
            // Composer package
            return MarkdownExtra::defaultTransform($this->text);
        }
    } 

Typical usage:

    // `MarkdownFile::new()` creates new instance of the class, 
    // just as `new MarkdownFile()` would do, plus it applies dynamic traits
    $file = MarkdownFile::new(['path' => 'welcome.md']);
    
    echo $file->html;
    
While accessing formally undefined `html` property for the first time, PHP internally creates it and assigns it a value computed using `get_html()` method. On subsequent access, PHP just returns previously computed property value. The same happens with `text` and `absolute_path` properties.

Computed properties save precious CPU cycles. On one hand, property values are only computed if they are actually accessed. On the other hand, some properties are accessed hundreds or even thousands times while handling a single HTTP request, and thanks to very fast subsequent access these properties have significant performance increase.

Computed properties are also test-friendly. In the following example, the unit test fully concentrates on HTML transformation by omitting `text` property computation - and all the file handling - by providing its value in the constructor:

    public function test_markdown_transformation() {
        // GIVEN a bold text written in Markdown
        $file = MarkdownFile::new(['text' => '**test**']);
        
        // WHEN you convert it to HTML
        // THEN it is marked with `<strong>` HTML element
        $this->assertEquals('<p><strong>test</strong></p>', $file->html); 
    }     

## More Results With Less Effort Using Reflection 

With Osm Framework, you develop faster by letting it to infer mundane things from class definitions. 

For example, in order to introduce new console command, you only have to define a class extending the `Command` class, and the framework adds it to the system automatically.
It also inspects property definitions, finds the `Option` and `Argument` attributes, and exposes them as command-line options and arguments:

    /**
     * @property bool $caps #[Option] If specified, the person name is upper-cased
     * @property string $person_name #[Argument] The person to greet
     */
    class Hello extends Command
    {
        public string $name = 'hello';
        public string $description = 'A sample command';
    
        public function run(): void {
            $name = $this->caps ? strtoupper($this->person_name) : $this->person_name;
            $this->output->writeln("Hello, {$name}");
        }
    }
    
With the `gulp watch` running, you can use the command without further ado:

    >osm hello vo
    Hello, vo
    
    >osm hello vo --caps
    Hello, VO 

The other example stores the property in the application cache just by marking property as `Cached`:

    /**
     * @property string $cached_property #[Cached('my_cache_entry')]
     */
    class MyClass extends Object_
    {
        protected function get_cached_property(): string {
            ...
        }
    }       
    
## Exceptional Performance

Osm Framework is very fast, for two reasons.

First, it offloads performance-hungry parts into pre-execution (or "compilation") phase, and aggressively uses caching techniques.

Second, where it really makes a difference, it puts performance first, sometimes even above established programming practices. 

One example is implementation of computed properties. The implementation is really fast, but it sacrifices encapsulation principle - the computed properties are public.

Another example is `$osm_app` global variable. Global variables in general are a known anti-pattern. However, as tests have shown, replacing `get_app()` accessor function with direct variable access gives significant performance boost, and, hence, the `$osm_app`
global variable became the main internal API entry point.   