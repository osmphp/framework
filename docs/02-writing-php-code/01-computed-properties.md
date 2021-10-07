# Computed Properties

Computed properties are one of Osm Framework pillars. They help to execute code only once, and only if it's actually needed. Computed properties control object serialization and caching. They provide meaningful insights into class interdependencies. Finally, computed properties are easy to test.

Contents:

{{ toc }}

### meta.abstract

Computed properties are one of Osm Framework pillars. They help to execute code only once, and only if it's actually needed. Computed properties control object serialization and caching. They provide meaningful insights into class interdependencies. Finally, computed properties are easy to test.

## Example

Let's start with an example. 

Consider a class that reads a Markdown file and transforms into HTML:

	/**
	 * @property string $path Required. Relative file path in the `data` directory. 
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

The class is used as follows:

	// `MarkdownFile::new()` creates new instance of the class, 
	// just as `new MarkdownFile()` would do, plus it applies dynamic traits
	$file = MarkdownFile::new(['path' => 'welcome.md']);
	
	echo $file->html;

In the example above, `path`, `absolute_path`, `text` and `html` are *computed properties*. 

`path` property is assigned in the constructor.

`absolute_path`, `text` and `html` properties are computed in the `get_absolute_path()`, `get_text()`, and `get_html()` methods (known as *computed property getters*), respectively. 

## How The Example Actually Works

Let's examine what happens when the following files are executed:

	$file = MarkdownFile::new(['path' => 'welcome.md']);
	echo $file->html;
 
The first line creates an instance of `MarkdownFile` class, and assigns a value to the `path` property. The rest properties are not assigned, they don't even exist yet.

The second line tries to access `html` property, but it doesn't exist! Hence, it calls the getter, `get_html()` method: 

    protected function get_html(): ?string {
        return MarkdownExtra::defaultTransform($this->text);
    }

This method retrieves value of the `text` property, but it doesn't exist either! Again the property getter is called:

    protected function get_text(): string {
        return file_get_contents($this->absolute_path);
    }

This getter refers to yet another non-existent property, `absolute_path`, and again, the getter is called:

    protected function get_absolute_path(): string {
        global $osm_app; /* @var App $osm_app */
        return "{$osm_app->paths->data}/posts/{$this->path}";
    }

Finally, this getter accesses the `path` property that exists and is assigned, so it creates the `absolute_path` property and assigns the computed value to it.

Back in the `get_text()` method, the computed value of the `absolute_path` property is successfully used. The `text` property is created and assigned. 

The same happens in the `get_html()` method.  

## Properties Are Computed Only Once

Computed property getters are only executed on first access. The computed value is stored in the object, and on subsequent access, the stored value is used.

It works in similar fashion as the code below:

	protected string $absolutePath = null;

	public function getAbsolutePath(): string {
		if ($this->absolutePath === null) {
			$this->absolutePath = ...;
		}
	
		return $this->absolutePath;
	} 

It's also worth mentioning that if a computed property is not used, its getter is not called at all.

## Computed Properties Are Read-Only

Don't directly assign a value to a computed property like this:

	// bad idea!
	file->text = 'foo';

This may lead to code that is hard to maintain. 

## Debugging Accidental Property Assignments

However, you may assign a value to a computed property accidentally. Let's say, you've just found out that property `Foo:$bar` is assigned somewhere in the codebase, but you don't know where exactly. In order to find out the exact location, use `DebuggableProperties` trait:

    use Osm\Core\Traits\DebuggableProperties;
    ...
    /**
     * @property string $bar
     */
    class Foo extends Object_ {
        use DebuggableProperties;
    }

Then add a breakpoint to `DebuggableProperties::__set()` method:

    public function __set(string $property, $value): void {
        $this->__data[$property] = $value; // <- add breakpoint here
    }

The debugger will hit this breakpoint when the property is assigned. 

## Assigning Properties In Constructor

Assign computed properties in the constructor:

    MarkdownFile::new(['path' => 'welcome.md']); 

There are two things worth mentioning.

First, use `MarkdownFile::new()` static method instead of calling `new MarkdownFile()` constructor directly. It allows the framework applying dynamic traits.

Second, assign only required properties. Class documentation should be explicit about which properties are required:

	/**
	 * @property string $path Required. ...
	 * ...
	 */
	class MarkdownFile extends Object_ {
	    ...
	}

In unit tests, you may also want to assign properties that in real-life scenarios are always computed. See also [Testing Computed Properties](#testing-computed-properties).     

## Throwing An Exception If Property Is Not Assigned

In case the caller doesn't assign any value to a required property, throw `Required` exception in the property getter: 

    use Osm\Core\Exceptions\Required;
    ...
	/**
	 * @property string $path Required. ...
	 * ...
	 */
	class MarkdownFile extends Object_ {
	    protected function get_path(): string {
	        throw new Required(__METHOD__);
	    }
	    ...
	}

## Serializing Properties

Sometimes, objects are converted to a string, for example, for storing them in the application cache. Converting an object to a string is known as *serialization*:

    // create an object
    $object = Foo::new(['bar' => 'hello']);
    
    // serialize it to a string
    $serialized = serialize($object);
    
    // unserialize the string back to a object
    $object2 = unserialize($serialized);

By default, object properties are not serialized. As in the example above, `$object->bar` property doesn't get serialized. 

Mark serializable properties explicitly with the `Serialized` attribute:

    use Osm\Core\Attributes\Serialized;
    ...
    /**
     * @property string $bar #[Serialized]
     */
    class Foo extends Object_ {
        ...
    }

The same `Serialized` attribute applies to converting objects to JSON. Only properties that are marked with this attribute are preserved in the JSON object:

    use function Osm\dehydrate;
    ...
    
    // create an object
    $object = Foo::new(['bar' => 'hello']);
    
    // convert it to JSON
    $json = json_encode(dehydrate($object));
    
    // {"bar": "hello"}

## Cached Properties

Some properties take time to compute. It may be an issue even though computation is done once per HTTP request.

Put such properties into the application cache, and the next time Osm framework will take the property value from cache instead of computing it again:

    use Osm\Framework\Cache\Attributes\Cached;
      
    use Osm\Core\Attributes\Serialized;
    ...
    /**
     * @property string $bar #[Cached('bar')]
     */
    class Foo extends Object_ {
        protected function get_bar(): string {
            // resource intensive computation 
        }
    }

The first parameter in the `#[Cached('bar')]` attribute is the unique cache key. In case `Foo` class has multiple instances, add properties that uniquely identify each object to the cache key:

    /**
     * @property int $id Unique for each Foo instance 
     * @property string $bar #[Cached('bar_{id}')]
     */
    class Foo extends Object_ {
        ...
    }
    
    ...
    // the `bar` property is cached under the unique cache key `bar_1`
    $foo = Foo::new(['id' => 1]);

By default, the property value is stored in the application cache until it is deleted manually, or until the whole cache is cleared. For more fine-grained control, use optional parameters of the `#[Cached]` attribute: 

* assign one or more cache tags, so that you can delete all the cache entries having a specific tag;
* set expiry period after which the cache entry is deleted automatically;
* specify a method that prepares the property value after it's just been extracted from the cache.

Example:

    /**
     * @property int $id Unique for each Foo instance 
     * @property string $bar #[Cached('bar_{id}', tags: ['bars'], 
     *          expires_after: 30, callback: 'prepareBar')]
     */
    class Foo extends Object_ {
        protected function prepareBar(): void {
            // `$this->bar` is already extracted from the cache
            ...
        }
        ...
    }

## Testing Computed Properties

Computed properties are unit-test-friendly. 

Let's return to the `MarkdownFile` class example, and, specifically, to the `html` property: 

	/**
	 * ...
	 * @property string $html Text converted to HTML
	 */
	class MarkdownFile extends Object_ {
	    ...
	    protected function get_html(): ?string {
	        // convert the text into HTML using `michelf/php-markdown` 
	        // Composer package
	        return MarkdownExtra::defaultTransform($this->text);
	    }
	} 

You may test this property in isolation, by passing the properties required to compute `html` property, in the class constructor:

    public function test_html_property(): void {
        // GIVEN a single-paragraph Markdown
        $file = MarkdownFile::new(['text' => 'hello']);
        
        // WHEN you convert it to HTML
        $html = $file->html;
        
        // THEN the paragragh should be wrapped into the `<p>` HTML element
        $this->assertEquals('<p>hello</p>', $html);
    }

## Defining Class Dependencies

In general, it's a good idea to explicitly specify classes used by your class, or in other words, your class *dependencies*:

    /**
     * @property Db $db Uses the main application database
     */
    class Foo extends Object_ {
        protected function get_db(): Db {
            global $osm_app; /* @var App $osm_app */
            
            return $osm_app->db;
        }
    }

First, explicit dependencies makes your code easier to read.

Second, you may unit-test your class in isolation of other parts of the application. For example, you may [stub](https://phpunit.readthedocs.io/en/9.5/test-doubles.html#stubs) or [mock](https://phpunit.readthedocs.io/en/9.5/test-doubles.html#mock-objects) the actual database connection object:

    public function test_foo(): void {
        // GIVEN a mock object
        $db = $this->createMock(Db::class);
        // configure it
        ...
        
        // WHEN you use the `Foo` class
        $foo = Foo::new(['db' => $db]);
        ...
        
        // THEN the mock object will assert received method calls
    }