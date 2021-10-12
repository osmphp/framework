# Hint Classes

When dealing with plain PHP objects, be it some data read from a JSON, from a database record, or from a configuration file, use hint classes. A hint class is a class that is never directly instantiated. Instead, it provides symbol information to the IDE specifically PhpStorm. With this information, the IDE helps you avoid typing error, makes you faster using code completion, and provides contextual help.

More details:

{{ toc }}

### meta.abstract

When dealing with plain PHP objects, be it some data read from a JSON, from a database record, or from a configuration file, use hint classes. A hint class is a class that is never directly instantiated. Instead, it provides symbol information to the IDE suc as PhpStorm. With this information, the IDE helps you avoid typing error, makes you faster using code completion, and provides contextual help.

## Plain PHP Objects

Let's begin with some examples:

    global $osm_app; /* @var App $osm_app */
    
    // an object from a JSON
    $product = json_decode('{ "sku": "123", "qty": 1}');
    
    // an object from a database record
    $product = $osm_app->db->table('products')
        ->where('id', 1)
        ->first(['sku', 'qty']);

    // an object from `settings.php` that is defined as
    //
    //     return (object)[
    //         ...
    //         'product_defaults' => (object)[
    //             'qty' => 0,        
    //         ],
    //     ];  
    $defaults = $osm_app->settings->product_defaults;    

All this code works, but handling these objects is error-prone as the IDE has no clue what's inside these objects. For example, you may refer to non-existing property `$product->quantity` instead of `$product->qty`, and the error will only show itself at runtime. 
        
## Defining Hint Classes

In order to provide symbol information to the IDE, define a hint class. By convention, put it into the `Hints/` subdirectory of your module:

    <?php
    
    namespace My\Base\Hints;
    
    /**
     * @property string $sku Unique product identifier
     * @property int $qty Quantity in stock
     */
    class Product {
    }

## Using Hint Classes

### Type-Hinting Variables

After that, add `/* @var Type $variable */` type hints to your code:

    use My\Base\Hints\Product;
    ...
    
    global $osm_app; /* @var App $osm_app */
    
    // an object from a JSON
    /* @var Product $product */
    $product = json_decode('{ "sku": "123", "qty": 1}');
    
    // an object from a database record
    /* @var Product $product */
    $product = $osm_app->db->table('products')
        ->where('id', 1)
        ->first(['sku', 'qty']);

    // an object from `settings.php` that is defined as
    /* @var Product $defaults */
    $defaults = $osm_app->settings->product_defaults;    

Now if you type `$qty = $product->qty;`, good things happen:

* the IDE helps you to complete the property name `qty` correctly;
* you can navigate to the `qty` property definition in the hint class;
* you can invoke a popup showing the property type and description.  

### Type-Hinting Properties And Methods

In properties, method parameters, and method return values, use `\stdClass|Product` union type. The former is for the PHP runtime - that's the actual type. The latter is for the IDE - that's a hint class with symbol information.   

Let's continue our example. Let's say, there is class, `Stock`, that checks stock availability, and, upon request, can offer a substitute.  

    /**
     * @property \stdClass|Product[] $products
     */
    class Stock {
        public function reserve(\stdClass|Product $product): void {
            ...
        }
        
        public function substitute(\stdClass|Product $product)
            : \stdClass|Product
        {
            ...
        }
    }
 
## Extending Hint Classes

You may extend existing hint classes by adding new properties to them using a dynamic trait. 

Back in the initial example, we used undefined `$osm_app->settings->product_defaults` property. `$osm_app->settings` is a plain PHP object itself, type-hinted by [`Settings`](https://github.com/osmphp/framework/blob/HEAD/src/Settings/Hints/Settings.php) hint class. Let's define the `product_defaults` property in it:

    <?php
    
    namespace My\Base\Traits;

    use Osm\Core\Attributes\UseIn;
    use Osm\Framework\Settings\Hints\Settings;   
    use My\Base\Hints\Product; 
    
    /**
     * @property \stdClass|Product $product_defaults
     */
    #[UseIn(Settings::class)]
    trait SettingsTrait {
    } 

After a gulp, the IDE starts recognizing `product_defaults` property.

## Hydration And Dehydration

Don't confuse plain objects with typed objects. 

A typed object is an instance of a class that derives from `Object_` class, and as such may have its own methods, including computing property getters. On the contrary, a plain object has no methods, no computed properties, it's just a bag with data. 

If you need the plain object to have behavior, convert, or *hydrate*, it into a typed object.  

Let's say, that in our example, in addition to `My\Base\Hints\Product` hint class, there is also a `My\Base\Product` ordinary class with some behavior:

    use Osm\Core\Attributes\Serialized;
    ...
    /**
     * @property string $sku #[Serialized] Unique product identifier
     * @property int $qty #[Serialized] Quantity in stock
     * @property bool $in_stock Specifies whether the product is salable
     */
    class Product extends Object_ {
        protected function get_in_stock(): bool {
            return $this->qty > 0;
        }
    }
Hydrate the plain PHP object by passing it as an array to the constructor of the ordinary class:

    use My\Base\Hints\Product as ProductHint;
    use My\Base\Product;
    ...
    /* @var ProductHint $item */
    $item = $osm_app->db->table('products')
        ->where('id', 1)
        ->first(['sku', 'qty']);
        
    $product = Product::new((array)$item); 

And vice versa, if you need, let's say, to pass a typed object over the network, convert, or *dehydrate*, it to a plain object using `dehydrate()` helper function:

    use function Osm\dehydrate;
    ... 
    $item = dehydrate($product);    

The `dehydrate()` function only copies the properties marked with `#[Serialized]` attribute.