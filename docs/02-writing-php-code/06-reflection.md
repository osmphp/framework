# Reflection

PHP reflection is great. However, it comes with limitations: there is no efficient way of enumerating all descendants of a certain class, and it doesn't work with `@property` declarations. Osm Framework addresses these limitations, and provides a very fast reflection API that powers its metaprogramming features.

Details:

{{ toc }}

### meta.abstract

PHP reflection is great. However, it comes with limitations: there is no efficient way of enumerating all descendants of a certain class, and it doesn't work with `@property` declarations. Osm Framework addresses these limitations, and provides a very fast reflection API that powers its metaprogramming features.

## Introduction

Let's illustrate how the reflection is typically used on the example of a console application.

In order to define a console command in Osm Framework, you only need to define a class that extends [`Command`](https://github.com/osmphp/framework/blob/HEAD/src/Console/Command.php) class, and specify its name:

    class Hello extends Command {
        public string $name = 'hello';
        
        public function run(): void {
            $this->output->writeln('Hello, world!');
        }
    }        

After it's defined, you can use this command:

    osm hello
    
**How it works**. Osm Framework collects all [descendants](#descendants) of the `Command` class, and configure the console application with all found commands.

Further, in order to define an argument, or an option the command accepts, all you need to do it to define a property, and mark it with `#[Argument]` or `#[Option]` attribute:

    use Osm\Framework\Console\Attributes\Argument;
    use Osm\Framework\Console\Attributes\Option;
    ...
    /**
     * @property string $person #[Argument]
     * @property bool $caps #[Option]
     */
    class Hello extends Command {
        public string $name = 'hello';
        
        public function run(): void {
            $person = $this->caps
                ? mb_strtoupper($this->person)
                : $this->person;
                
            $this->output->writeln("Hello, {$person}!");
        }
    }        
  
**How it works**. Osm Framework analyzes attributes assigned to properties, and their types, and registers all marked properties as command attributes and options. When the command is executed Osm Framework fills in provided arguments and options into the properties of the command object:

    > osm hello Joe --caps
    Hello, JOE!  

All of these things that greatly simplify console application development, are made available by reflection. 

## Classes

**Note**. First and foremost, Osm Framework collects information only about classes that is contained in modules of the current application.

Get the reflection information about a class using `$osm_app->classes` property, indexed by full class name:

    use Osm\Core\App;
    use My\Base\Foo;
    ...
    global $osm_app; /* @var App $osm_app */
    ...
    $class = $osm_app->classes[Foo:class];
    
Alternatively, get the reflection information about a class of a specific object `$foo` using its `__class` property:

    $class = $foo->__class;
    
Once you obtained the class information (it's an instance of [`Class_`](https://github.com/osmphp/core/blob/HEAD/src/Class_.php) type), explore it further using its properties:

* `name` - the class name, `My\Base\Foo`
* `generated_name` - the name of a generated class that is actually instantiated instead of `My\Base\Foo` in order to apply [dynamic traits](03-dynamic-traits.md).
* `attributes` - array of class [attributes](#attributes), indexed by attribute class name.
* `properties` - array of class properties indexed by property name. Contains both regular PHP properties, and the ones introduced using `@property` syntax.  
* `methods` - array of class methods, indexed by method name.

## Properties

Get the reflection information about a property using `$class->properties` property, indexed by property name:

    use Osm\Core\App;
    use My\Base\Foo;
    ...
    global $osm_app; /* @var App $osm_app */
    ...
    $class = $osm_app->classes[Foo:class];
    $property = $class->properties['bar'];

**Note**. `$class->properties` contains properties defined in the specified class, properties inherited from parent classes, and properties from all dynamic traits applied the specified class and its parent classes. It contains not only regular PHP properties, but also the ones introduced using `@property` syntax.

Property object, an instance of [`Property`](https://github.com/osmphp/core/blob/HEAD/src/Property.php) class, contains the following information:

* `name` - property name.
* `type` - property type. If the property is an array, then the `type` specifies the type of array items.
* `array` - a boolean flag indicating whether the property is an array.
* `nullable` - a boolean flag indicating whether the property is optional and can return `null` value.
* `attributes` - array of property [attributes](#attributes), indexed by attribute class name. 
* `class_name` - the full name of the class containing this property.

## Methods

Regarding method reflection, Osm Framework has nothing much to add. Obtain information about class methods using standard PHP `ReflectionMethod` class:

    use My\Base\Foo;
    ...
    $class = new \ReflectionClass(Foo::class);
    $method = $class->getMethod('bar');
    
For more information, refer to [PHP documentation](https://www.php.net/manual/en/class.reflectionmethod.php).     

## Attributes

Get the information about PHP 8 attributes applied to a class, o a property using `$class->attributes` and `$property->attributes` properties, respectively, both indexed by the attribute class name:

    use Osm\Core\App;
    use My\Base\Foo;
    use Osm\Core\Attributes\Name;
    ...
    global $osm_app; /* @var App $osm_app */
    ...
    $class = $osm_app->classes[Foo:class];
    $property = $class->properties['bar'];
    $attribute = $property->attributes[Name::class] ?? null;

The `$property->attributes[Name::class]` returns attribute instance. If a given attribute is not applied, `$property->attributes[Name::class]` is not set. 
    
If the attribute class `RepeatableAttribute` is marked with `Attribute::IS_REPEATABLE` flag, then `property->attributes[RepeatableAttribute::class]` returns an array all attribute instances rather than a single instance:

    ...
    foreach ($property->attributes[RepeatableAttribute::class] ?? []
        as $attribute)
    {
        ...
    }   

The contents of each attribute instance is defined by its class. For example, the [`Name`](https://github.com/osmphp/core/blob/HEAD/src/Attributes/Name.php) attribute class, often used in Osm Framework to give a class some short, yet unique name, has a single `name` property:

    #[\Attribute(\Attribute::TARGET_CLASS)]
    final class Name
    {
        public function __construct(public string $name) {
        }
    }

Given an attribute is applied to a class:

    #[Name('foo')]
    class Foo extends Object_ {
    }

When you reflect over the `Foo` class, get the assigned unique name `foo`:

    use Osm\Core\App;
    use My\Base\Foo;
    use Osm\Core\Attributes\Name;
    ...
    global $osm_app; /* @var App $osm_app */
    ...
    $class = $osm_app->classes[Foo:class];
    $name = $property->attributes[Name::class]?->name;

## Descendants

Use `$osm_app->descendants` property for collecting all classes deriving from a specified base class:

    use Osm\Core\App;
    use My\Base\Foo;
    use Osm\Core\Attributes\Name;
    ...
    global $osm_app; /* @var App $osm_app */
    ...
    $classes = $osm_app->descendants->classes(Foo::class); 
    
If, by design, all derived classes are assigned a unique name using the `#[Name]` attribute, use `byName()` method to get the class names indexed by the assigned unique name:

    $classNames = $osm_app->descendants->byName(Foo::class);
    
## Performance

All examples, presented above, work really fast, because all the reflection information is collected during compilation phase, and at runtime, it's only unserialized from the `generated/{app}/app.ser`. It means that it doesn't incur any significant runtime cost.

