# Request-Response Loop

{{ toc }}

## How The Web Works

We couldn't explain it better than [Symfony did](https://symfony.com/doc/current/introduction/http_fundamentals.html). Here is the short version:

1. When you open a link to your website in a browser, the browser sends to your server an *HTTP request*. A request is a plain-text string, containing:

    * *method*, mostly `GET` or `POST`, 
    * *URL* - what page the browser wants to render
    * *headers* - some system information about the browser and its settings, *cookies*, and other
    * and optional *body*, for example, data entered into a form.   

2. The request is handled by a [Web server](../01-getting-started/06-web-server.md) - a special program that runs on your server, receives browser requests, and handles them. While the Web server can handle most requests on its own (for example, serve image, CSS, or JS files), it asks PHP to handle the rest. Either way, the Web server returns a plain-text string *response* to the browser that contains:

    * *status code*, `200` if everything went OK, `404` if there is no such page, and so on.
    * *headers* - some system information about *content type* (is it some HTML, an image a CSS file, or other), *cookies*, *caching instructions*, and other.
    * *body*, for example, the HTML that should be rendered in the browser.

3. The browser renders the response.

And that's it. If you click on another link, the browser sends another request, and the Web server sends the page HTML as another response. If a browser finds some image or a link to a CSS file, it sends yet another requests, and the Web server sends requested image and CSS files as responses.  

## Processing Requests

Osm Framework parses each raw request text delegated by the Web server, and puts it into `$osm_app->http->request` property of [`Request`](https://github.com/symfony/symfony/blob/HEAD/src/Symfony/Component/HttpFoundation/Request.php) type. 

Then it processes the request in the `run()` method of the [`Http`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Http.php) class:

    public function run(): Response {
        $this->running = true;

        try {
            return $this->module->around(function() {
                $this->detectArea();

                return $this->module->around(function() {
                    $this->detectRoute();

                    return $this->route->run();
                }, $this->area_class_name);
            });
        }
        finally {
            $this->running = false;
        }
    }
    
As you see, the request is processed in two steps:

1. An *area* is detected.
2. A *route* within the area is detected and executed.

The [`Response`](https://github.com/symfony/symfony/blob/HEAD/src/Symfony/Component/HttpFoundation/Response.php), returned by the `Http::run()` method, is converted to raw text format, and passed back to the Web server, which sends it to the browser.

### Areas

An application may have several *areas*. Typically, *front* area is for website visitors, *admin* area is website administrators, and *api* area is for external applications using your application API.

By default, all requests are considered to be in the front area. If the URL path starts with `/admin`, then it's considered to be in the admin area, if it starts with `/api`, then it goes to the api area. If needed, change this default behavior by applying a dynamic trait to the `get_base_urls()` method of the [`App`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Traits/AppTrait.php) class, or to the `detectArea()` method of the [`Http`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Http.php) class.  

Once detected, the current area can be retrieved using the `$osm_app->http->area` property:

    global $osm_app; /* @var App $osm_app */
    
    $area = $osm_app->http->area;
    
### Routes

A *route* is a class that knows how to process a specific request. It specifies area the route belongs to, an HTTP method and path that uniquely identifies the route, and the logic that creates a response. By convention, put route classes into the `{module_dir}/Routes/{Area}` directory:

    ...
    namespace My\Base\Routes\Front;

    use Osm\Core\Attributes\Name;
    use Osm\Framework\Areas\Attributes\Area;
    use Osm\Framework\Areas\Front;
    use Osm\Framework\Http\Route;
    use Symfony\Component\HttpFoundation\Response;
    
    #[Area(Front::class), Name('GET /test')]
    class Test extends Route
    {
        public function run(): Response {
            return new Response('<p class="test">Hi</p>');
        }
    }  

## Reading Request Details

### Query Parameters

Often, a request URL contains a *query* - an additional data string:

    GET /test?param1=value1&flag2&param3=value3
    
It starts with a `?`. It contains parameters (`param1=value`) and boolean flags (`flag2`), delimited by `&`. 

Read query parameters using `$osm_app->http->query`. There are 3 types of parameters: regular, flag, and array parameters:

    global $osm_app; /* @var App $osm_app */
    
    $query = $osm_app->http->query;
    
    // regular URL query parameters, such as `?param=value`, return string value
    $param = $query['param'] ?? null;
     
    // URL query parameters without a value, for example `?flag`, return `true`
    $flag = $query['flag'] ?? false; 

    // URL query parameters that are mentioned several times in the same query,
    // for example, `?color=red&color=green`, return an array
    $color = (array)($query['color'] ?? []);

### Body

Some requests contain a *body*, for example, form data, or a JSON to be passed to the API. Read it using `$osm_app->http->content` property:

    global $osm_app; /* @var App $osm_app */
    
    $json = json_decode($osm_app->http->query);

### Other

For other request data, use [`$osm_app->http->request`](https://symfony.com/doc/current/components/http_foundation.html#request) property:

    global $osm_app; /* @var App $osm_app */
    
    // get the browser type
    $userAgent = $osm_app->http->request->headers->get('User-Agent');

## Creating Responses

Every route should return a `Response` object. You can create it manually, as demonstrated above, or use one of the helper functions.

### HTML 

Render HTML using Blade template engine, and return it as a `Response` object using `view_response()` function:

    use function Osm\view_response;
    ...
    class Test extends Route
    {
        public function run(): Response {
            return view_response('base::pages.test', [
                ...
            ]);
        }
    }  
 
For more information about Blade templates, read [Views And Components](02-views-and-components.md).

### JSON

Send a JSON response using `json_response()` function:

    use function Osm\json_response;
    ...
    class Test extends Route
    {
        public function run(): Response {
            return json_response((object)[
                'sku' => '123',
                'qty' => 5,
            ]);
        }
    }  

### Not Found

Send 404 response indicating that the requested page doesn't exist on the server by throwing `NotFound` exception:

    use Osm\Framework\Http\Exceptions\NotFound;
    ...
    class Test extends Route
    {
        public function run(): Response {
            throw new NotFound();
        }
    }  


### Error

Send 500 response indicating that there has been some error on the server by throwing any other exception:

    use Osm\Framework\Http\Exceptions\NotFound;
    ...
    class Test extends Route
    {
        public function run(): Response {
            throw new \Exception("Error");
        }
    }  

All such exceptions are also written to `temp/Osm_App/logs/http-*.log` files.

## Dynamic Routes

So far, example route classes had 1:1 mapping to exact incoming URLs. In some search engine optimized scenarios, you have to handle lots of different incoming URLs using the same route class. Such route is called *dynamic route*.

One example of a dynamic route is this very documentation. Every documentation page has a different URL, for example, `/docs/framework/0.13/creating-web-applications/request-response-loop.html`, and yet, it's handled using the same route that, basically, finds a Markdown file matching the incoming URL, and renders it.

Extend your dynamic route from the `DynamicRoute` class, define the `#[Area]`, but don't specify any URL path using the `#[Name]` attribute. Instead, configure a dynamic dispatcher in the `get_dispatcher()` method:

    #[Area(Front::class)]
    class Dynamic extends DynamicRoute
    {
        protected function get_dispatcher(): Dispatcher {
            return simpleDispatcher(function (RouteCollector $r) {
                ...
            });
        }
    } 

Inside the `simpleDispatcher()` callback, use methods of the [`RouteCollector`](https://github.com/nikic/FastRoute) class. For example, in order to handle any `*.html` page request, write:

    $r->get('/{path:.*\.html}', RenderPage::class);
    
The third parameter, `RenderPage::class`, specifies a route class that should handle the `*.html` page requests. Unlike other route classes, the `RenderPage` class don't have to specify neither `#[Area]`, nor `#[Name]` attributes. 

Instead, define a `@property` for each pattern variable. In the example, it's `path`:

    /**
     * @property string $path
     */
    class RenderPage extends VersionRoute {
        ...
    }
 
You can also pass more property values into the `RenderPage` route using the array syntax in the third argument. For example, assign `RenderPage:$foo` property:

    $r->get('/{path:.*\.html}', [RenderPage::class => ['foo' => 'bar']]);

See also real-world [blog](https://github.com/osmphp/osmsoftware-website/blob/HEAD/packages/blog/Posts/Routes/Front/Dynamic.php) and [documentation](https://github.com/osmphp/osmsoftware-website/blob/HEAD/packages/docs/Docs/Routes/Front/Dynamic.php) examples of dynamic routing. 

## Advices

Back in the [`Http::run()`](#processing-requests) method, you may have noticed that both area detection, and route execution are wrapped into `$this->module->around()` method calls.

This method runs additional code, known as *advices* before, after, and sometimes instead of wrapped logic. Advice is a class that extends the [`Advice`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Advices/Advice.php) class, and specifies:

* the area it should be applied to, or `null` if it's applied before area detection;
* the sort order used to determine in what order all registered advices should be executed;
* the `around()` method that executes the logic around the wrapped code.

Foe example, [`CatchExceptions`](https://github.com/osmphp/framework/blob/HEAD/src/Http/Advices/CatchExceptions.php) advice executes the wrapped code (that is, the area detection, and the matching route), and in case an exception is raised, sends 404 or 500 response to the browser:

    ...
    #[Area(null, 10)]
    class CatchExceptions extends Advice
    {
        public function around(callable $next): Response {
            try {
                return $next();
            }
            catch (Http $e) {
                return $e->response();
            }
            catch (\Throwable $e) {
                return exception_response($e);
            }
        }
    }
 
In order to run the advice logic before the route, put it before the `$next()` callback call:

    public function around(callable $next): Response {
        // your logic
        
        return $next();
    }

In order to run the advice logic after the route, put it after the `$next()` callback call:

    public function around(callable $next): Response {
        $response = $next();
        
        // your logic
        
        return $response;
    }

If you want to completely replace the route, replace the route's response with your own:

    public function around(callable $next): Response {
        $response = $next();
        
        // your logic
        
        return new Response(...);
    }
