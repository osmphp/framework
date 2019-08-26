# Routes
  
**Route** is an object responsible for linking specific type of [HTTP request](../http-requests/) 
 and application components which should handle it.

{{ toc }}

{{ child_pages }}

## Understanding Routes

Web application receives [HTTP requests](../http-requests/) and returns [HTTP responses](#). It works as follows:

1. **Route** is found in list of all defined routes by combination of [HTTP request](../http-requests/) method and URL path.
2. **[Controller](../controllers/) method**, specified in found route is executed.
3. **[HTTP response](#)**, based on result of controller method execution, is returned to browser. 

Below are examples of route names:

	http://127.0.0.1/dubysa/?param1=value					=> 'GET /'
	http://127.0.0.1/dubysa/jim								=> 'GET /jim'
	http://127.0.0.1/dubysa/some-other-person?param1=value	=> 'GET /some-other-person'

> **Note**. `GET` here is HTTP request method. All URLs directly entered in browser address bar are requested using `GET` method. Other methods: `POST`, `PUT`, `DELETE`, sent when submitting forms or making AJAX requests, are out of scope of this tutorial. 

> **Note**. Routes are grouped into application [areas](#). This tutorial works in default application area called `web`.  

List of all routes is defined in application configuration. You can print it using the following command:

	php run show:config web/routes 

You should see something like:

	[
		'GET /profiler/plain-text' => [
			'class' => 'Osm\Framework\Profiler\Controllers\Web',
			'method' => 'plainTextPage',
			'public' => true,
			'returns' => 'plainText',
			'parameters' => [
				'id' => [
					'class' => 'Osm\Framework\Http\Parameters\String_',
					'required' => true,
				],
			],
		],
	]

Each route specifies which method of which class should run when route is requested. For instance, standard route `'GET /profiler/plain-text'` is handled by `Osm\Framework\Profiler\Controllers\Web::plainTextPage()` method. Such classes are called **controller classes** and methods handling HTTP requests are called **controller methods**.

> **Note**. If you enter `http://127.0.0.1/dubysa/profiler/plain-text` in a browser, you will still see "Page not found" message. Don't worry, this route exists and works, but to show something meaningful it requires [profiler](#) to be enabled.

## Route Configuration

Module routes are configured in `<module path>/config/<area name>/routes.php` file. 
 
Configuration includes route name and specification of handling the route.

**Route name** is a combination of HTTP request method and a path.  

Each route specifies which method of which class should run when route is requested.
 
Example of route definition in Dubysa:
 
        'GET /show' => [
             'class' => Web::class,
             'method' => 'show',
             'public' => true,
             'abstract' => true,
             'parameters' => [
                 'page' => [
                     'class' => Parameters\String_::class,
                     'required' => true,
                 ],
             ],
         ],
 
 where 
 
 - `GET` - HTTP method `GET`
 - `/show` - path we will process
 - `'class' => Web::class` - controller class name 
 - `'method' => 'show'` is the method in `Web` class which will handle this route
 - `'public' => true` means that this method does not require user authentication. If method is not public, this request will automatically be redirected to login page
 - `'abstract' => true` tells to ignore this route while matching incoming URL. Mostly used for handling pages with SEO-friendly URL, see [lesson-06 SEO Friendly URL](#)  
 - `'parameters'`  is an array of all parameters processed by `show` method. 
 
    Parameter values are handled by `Osm\Framework\Http\Parameters` class and are validated according it type. 
    
    For instance if we expecting integer, but string of characters is sent, error will be raised. 
    
    `Parameters\String_::class` can have additional validation according defined regular expression pattern.    
 
 	`page` parameter in example above is marked as `requied`, which means application cannot process `GET /show` request without it.

