# Lesson 6: Handling SEO-friendly URLs#

**URL** is a human-readable address of the web page, consists of four main parts: 

`<protocol><hostname>/<path>?<query parameters>`

Right now, our application can process 

`http://127.0.0.1/docs/?page=/introduction/installation.html` URL, where: 


* **protocol** is `http://` 
* **hostname** is `127.0.0.1`
* **path** is `docs/`
* **query parameters** part is `?page=/introduction/installation.html`


However, good and readable URL provides better understanding what the page will be about. 
Compare the example above with this:

`http://127.0.0.1/docs/introduction/installation.html`, where: 

* **protocol** is `http://` 
* **hostname** is `127.0.0.1`
* **path** is `docs/introduction/installation.html`

You can see, that by eliminating query parameter part, we have more readable URL, which gives better understanding of hierarchical structure of documentation website.
Keeping simple and elegant URL structure, avoiding URL parameter part, as well, will increase each page ranking factor for search engines.


In this lesson we are going to refactor our application to process SEO-friendly URLs.

Expected Result
----------------------------------------

|old HTTP request    |new HTTP request  ||
:----------- | :------------- | :----------- |
1. Home page | `GET /?page=/`| `GET /` |
2. First-level page	| `GET /?page=/some-page.html` | `GET /some-page.html` |
3. Second and greater level pages | `GET /?page=/some-page/child-page.html` | `GET /some-page/child-page.html` |


**Examples:**  

 1. **Home page:** <http://127.0.0.1/docs/> 

 2. **First-level page:**  <http://127.0.0.1/docs/introduction.html> 
       
 3. **Second=level page:**  <http://127.0.0.1/docs/introduction/installation.html> 

How HTTP Requests Are Processed
----------------------------------------

In lesson 1 we learned that our application for every incoming request finds a route matching the request and executes its controller method.

It is only part of the story, actually. Before and after execution of controller method additional logic is executed, called **HTTP advices**. Stack trace below shows actual execution of advices and controller method: 

	add_profile_header:before
		detect_area:before
			redirect_to_trailing_slash:before
				detect_route:before
					start_session:before
						# CONTROLLER METHOD
					start_session:after
				detect_route:after
			redirect_to_trailing_slash:after
		detect_area:after
	add_profile_header:after

In this stack, `detect_route`, as the name implies, detects route matching incoming request. Its implementation (in `Manadev\Framework\Http\Advices\DetectRoute` class) is quite simple - before calling the `$next` advice it finds the route in the array by key or throws an exception if key is not defined:

    public function around(callable $next) {
        global $m_app; /* @var App $m_app */

        if (!isset($m_app->controller)) {
            $m_app->controller = $this->findController();
        }

        return $next();
    }

    protected function findController() {
        if (!isset($this->area->controllers["{$this->request->method} {$this->request->route}"])) {
            throw new NotFound(m_("Page not found"));
        }

        $controller = $this->area->controllers["{$this->request->method} {$this->request->route}"];

        if ($controller->abstract) {
            throw new NotFound(m_("Page not found"));
        }

        return $controller;
    }

To implement SEO friendly URL handling, we need to modify `findController()` method, so that it would find and execute our `GET /` route in case incoming request matches some `.md` file on the disk.

However, it is not a good idea to edit source code of the framework directly. There is much more elegant way of doing that - dynamic [traits](https://www.php.net/manual/en/language.oop5.traits.php).

How PHP Classes Can Be Customized
----------------------------------------

In Dubysa you can customize execution of every `public` or `protected` method of almost every class by adding code executing before the method, after the method or even instead of the method. 

To customize method `x()`, you define a trait with a method `around_x()`:

	trait Customization {
		protected function around_x(callable $proceed) {
			// add logic to be executed before original method here

			// call original method x()
			$result = $proceed(); 

			// add logic to be executed after original method here

			return $result;
		}
	}

After defining a trait, you can register it to be applied to the class you want to customize. 

It sounds like magic, but the implementation is actually quite simple.

Most classes, including `DetectRoute` class, are instantiated using `::new()` static method:

	$detectRoute = DetectRoute::new();

If dynamic trait is registered for the class:

1. the system generates customized class which extends `DetectRoute` class and overrides method `x()`;
2. customized class is instantiated instead of original `DetectRoute` class. 

You can check all the generated classes in `temp/development/cache/classes.php`.

Knowing all that, we are now ready to return to the tutorial and implement SEO-friendly URL feature.
    
Steps To Implement:
----------------------------------------

{{ toc }}

## Modifying `routes.php`

We will match and execute `GET /` route in new, customized way, and we don't want it to be matched using standard logic. To mark it as "not matchable" we will set `abstract` property.

As it will no longer participate in standard route matching, `page` parameter, defined in [the route configuration of Lesson 3](finding-documentation-file.html#adding-page-parameter-handling-to-the-route-configuration) is no longer needed.

New content of `app/src/Docs/config/frontend/routes.php`:

	<?php
	
	use App\Docs\Controllers\Frontend;
	
	return [
	    'GET /' => [
	        'class' => Frontend::class,
	        'method' => 'show',
	        'abstract' => true,
	    ],
	];

## Adding `DetectRouteTrait` Trait 

By convention, dynamic traits are named by the original class adding `Trait` suffix. Dynamic traits are defined in `Traits` subnamespace.

Create new directory `app/src/Docs/Traits` and a file `app/src/Docs/Traits/DetectRouteTrait.php`:

	<?php
	
	namespace App\Docs\Traits;
	
	use App\Docs\Controllers\Frontend;
	use App\Docs\Module;
	use App\Docs\PageFinder;
	use Manadev\Core\App;
	use Manadev\Framework\Http\Exceptions\NotFound;
	
	trait DetectRouteTrait
	{
	    protected function around_findController(callable $proceed) {
	        global $m_app; /* @var App $m_app */
	
	        try {
	            return $proceed();
	        }
	        catch (NotFound $e) {
	            $module = $m_app->modules['App_Docs']; /* @var Module $module */
	            $request = $m_app->request;
	            $pageFinder = $m_app[PageFinder::class]; /* @var PageFinder $pageFinder */
	
	            if ($request->method != 'GET') {
	                throw $e;
	            }
	
	            if ($page = $pageFinder->find($request->route)) {
	                $module->page = $page;
	
	                return Frontend::new(['route' => '/', 'method' => 'show'], null, $m_app->area_->controllers);
	            }
	
	            throw $e;
	        }
	    }
	
	}

Normally, when `GET /some-page/child-page.html` route is processes, it will not find this route, because only  `GET /` route is defined. 

However, `around_findController` function will catch `NotFound` exception and will try to find a source documentation file by calling `$pageFinder->find($request->route)` - the `find` function of new class `App\Docs\PageFinder`. Now we need to create this new class.

## Moving File Finding From `Frontend` Controller To New `PageFinder` Class

Create new PHP class `app/src/Docs/PageFinder.php`:

	<?php
	
	namespace App\Docs;
	
	use App\Docs\Hints\SettingsHint;
	use Manadev\Core\App;
	use Manadev\Core\Object_;
	use Manadev\Framework\Settings\Settings;

	/**
	 * @property Settings|SettingsHint $settings @required
	 * @property string $doc_root @required
	 */
	class PageFinder extends Object_
	{
	    protected function default($property) {
	        global $m_app; /* @var App $m_app */
	
	        switch ($property) {
	            case 'settings':
	                return $m_app->settings;
	            case 'doc_root':
	                return $this->settings->doc_root;
	        }
	
	        return parent::default($property);
	    }
	
	    /**
	     * Returns .md page file by URL or returns null if not found
	     *
	     * @param string $url
	     * @return Page
	     */
	    public function find($url) {
	        if ($url === '/') {
	            // home page is rendered from 'index.md'
	            if (is_file($filename = $this->doc_root . '/index.md')) {
	                return Page::new(['name' => $filename]);
	            }
	        }
	
	        if (mb_strrpos($url, '.html') !== mb_strlen($url) - mb_strlen('.html')) {
	            // if page URL doesn't end with configured '.html' suffix, show that page is not found
	            return null;
	        }
	
	        // page URL ends with '.html' suffix. Remove suffix from URL
	        $url = mb_substr($url, 0, mb_strlen($url) - mb_strlen('.html'));
	
	        // handle page path. There should always be at least one '/' in URL as all page URLs start with '/'.
	
	        // find position of last '/' in URL and define path and filename
	        $pos = mb_strrpos($url, '/');
	        $path = $this->doc_root . mb_substr($url, 0, $pos);
	        $filename = mb_substr($url, $pos + 1);
	
	        // if path is not a directory or filename is empty, show that page is not found
	        if (!is_dir($path) || !$filename) {
	            return null;
	        }
	
	        // iterate through all files in 'path' directory and find file with or without preceding sort order.
	        foreach (new \DirectoryIterator($path) as $fileInfo) {
	            // skip '.' and '..' directory items
	            if ($fileInfo->isDot() || $fileInfo->isDir()) {
	                continue;
	            }
	
	            if (preg_match("/(?:\\d+-)?" . preg_quote($filename) . "\\.md/u", $fileInfo->getFilename())) {
	                return Page::new(['name' => "{$path}/{$fileInfo->getFilename()}"]);
	                }
	            }
	
	        // If underlying directory doesn't exist we return that page doesn't exist
	        return null;
	    }
	
	}

You see that `settings` and `doc_root` properties are defined now in this class, as well as `find` function, which will return `Page` object if file was found according the route. 

## Registering `DetectRouteTrait` And Adding Module Global Variable `page`

New trait should be registered in the module. As well `page` property should be defined on module level.  
In `app/src/Docs/Module.php`:
	
	<?php
	
	namespace App\Docs;
	
	use Manadev\Core\Modules\BaseModule;
	use Manadev\Framework\Http\Advices\DetectRoute;
	
	/**
	 * @property Page $page @required
	 */
	class Module extends BaseModule
	{
	    public $traits = [
	        DetectRoute::class => Traits\DetectRouteTrait::class,
	    ];
	
	}

## Cleaning Up `Frontend` Controller

Let's do cleanup in `Frontend` Controller.

In `app/src/Docs/Controllers/Frontend.php`:

	<?php
	
	namespace App\Docs\Controllers;
	
	use App\Docs\Module;
	use App\Docs\Page;
	use App\Docs\Views\Html;
	use Manadev\Core\App;
	use Manadev\Framework\Http\Controller;
	
	/**
	 * @property Page $page @required
	 * @property Module $module @required
	 */
	class Frontend extends Controller
	{
	    protected function default($property) {
	        global $m_app; /* @var App $m_app */
	
	        switch ($property) {
	            case 'module':
	                return $m_app->modules['App_Docs'];
	            case 'page':
	                return $this->module->page;
	        }
	
	        return parent::default($property);
	    }
	
	    public function show() {
	        return m_layout(
	            [
	                '@include' => 'base',
	                '#page' => [
	                    'title' => $this->page->title,
	                    'content' => Html::new(['page' => $this->page]),
	                ],
	            ]
	        );
	    }
	}

Conclusion
----------------------------------------

After all steps are finished, check if you can see documentation content when SEOified URL is entered.
