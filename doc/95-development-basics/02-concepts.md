# Concepts

{{ toc }}

## HTTP request

Client browser sends HTTP requests, while server sends HTTP responses.

HTTP request contain **request method**, usually `GET` or `POST`. 
Any URL entered in browser address bar initiate `GET` HTTP requests to 
retrieve data from this URL.

Server sends HTML text back to browser to show the page.

**Request URL** is the address of the resource in web. 

In general URL consists of 4 parts `[base URL][/path][?query][#fragment]`

For example in URL `http://127.0.0.1/dubysa-docs/?param1=value`

 - base URL `http://127.0.0.1/dubysa-docs` is a local address of our project is hosted
 - path `/` means homepage of the project
 - query `?param1=value` define additional information to serve the request
 - this URL does not contain `#fragment` 
 
## Area

Area provides it's own functionality for dedicated group of users.
Each area can have own themes, process proper URLs and generate specific content.

- `Frontend` area provides web content to system end-user
- `Backend` is used by website administrator
- `API` area is used when users communicates with application by HTTP requests without user interface
- `Web` are is used if application has only one presentation area for all kind of users

 
## Route

HTTP requests are routed to the code that handles them.

`Route` is an object responsible for linking specific type of HTTP request 
and application components which should handle it.

Route name is a combination of `method` and a `path`.  
Routes are processing according **exact match** principle. 
If path provided in address line is exactly the same as it it described in configuration, 
it will be handled with described method. 
If at least one symbol is not matched, it will return error "404 - page not found".

Example of route definition in Dubysa:

       'GET /show' => [
            'class' => Web::class,
            'method' => 'show',
            'public' => true,
            'seo' => true,
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
- `'seo' => true`, our setting for this application to know if this request contains SEO-friendly parameters. 
- `'parameters'`  is an array of all parameters processed by `show` method. 

   Parameter values are handled by `Manadev\Framework\Http\Parameters` class and are validated according it type. 
   
   For instance if we expecting integer, but string of characters is sent, error will be raised. 
   
   `Parameters\String_::class` can have additional validation according defined regular expression pattern.    

	`page` parameter in example above is marked as `requied`, which means application cannot process `GET /show` request without it.

## Controllers



## Theme
<TODO> Here should go Dubysa **theme** concept description

Default theme is located in `vendor/dubysa/framework/src/Framework/BlankTheme/theme.php` 
where only name and area are defined. 
 

----------------------------------
----------------------------------
----------------------------------
----------------------------------
 - NodeJS
node is a runtime environment that lets you write JavaScript on the server-side. In addition to being used for web services, it is often used to build developer tools like the Ionic CLI.

  - npm
npm is the package manager for the Node JavaScript platform. It puts modules in place so that node can find them, and manages dependency conflicts intelligently. It allows you to install, share, and package node modules. Ionic can be installed with npm, along with a number of its dependencies.

		Typically when you start your new ionic app you will run on your console: npm install in order to install all the dependencies of your app.

  - Lazy Loading
Normally when a user opens a page, the entire page’s contents are downloaded and rendered in a single go. While this allows the browser or app to cache the content, there’s no guarantee that the user will actually view all of the downloaded content.

		So, that's where Lazy Loading plays an important role, instead of bulk loading all the content at once, it can be loaded when the user accesses a part of the page that requires it. With lazy loading, pages are created with placeholder content which is only replaced with actual content when the user needs it.

		Lazy loading sounds like a complicated process, but actually is very straight forward. Conceptually, we’re taking one segment of code, a chunk, and loading it on demand as the app requests it. This is a very framework agnostic take on things, and the finer details here come in the form of NgModules for Ionic apps. NgModules are the way we can organize our app’s pages, and separate them out into different chunks.
		
		