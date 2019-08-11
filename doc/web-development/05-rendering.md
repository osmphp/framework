# Rendering #

{{ toc }}

## Introduction ##

Dubysa comes with standard set of page components: menu bar, button, input control, popup menu, dialog box, snack bar message and more. These components are called **views**. It is also easy to define custom views.

Individual views can be composed into larger views, those - into even larger views. Topmost views are composed into HTML page (which is also view).
 
Consider this page:

![Application signup form](signup-form.png)

The page has menu with 2 elements, heading and a block of input fields together with action button.

For this page the hierarchical view tree is created:

* `Page` view, containing three child views - `Menu`, `Heading` and `Form`
   * `Menu` view for the top menu with two elements
      * `Item1` view - "HOME PAGE" menu element
      * `Item2` view - "LOGIN" menu element
 
   * `Heading` view - text with application name and instruction
   * `Form` view joins user input fields
      * `Input1` view - "Username" input control and accompanying text
      * `Input2` view - "Email" input control
      * `Input3` view - "Password" input control               
      * `Button` view - "SIGN UP" button

This hierarchical view tree is called **layout** of the page.

The layout is rarely created all in one step. Instead, some base layout is defined in the first step, then the second and further steps add more and more details to it (add child views, modify properties of existing views and more). Layout creation steps are called **layers**.
 
**Rendering** is just creating the layout and converting it into HTML string. 

## Views ##

View is just an ordinary PHP object with properties and methods, an instance of `Manadev\Framework\Views\View` or descendant class.  

To create a view object, use [`new` static method](#):

    use Manadev\Framework\Views\View;

    $view = View::new([
		'template' => 'My_Module.signup_form',
	]);

Template named `'My_Module.signup_form'` is used to convert view to HTML, more on that later in this article. 

### View Properties ###

Views may contain additional data which is used in the template:

    $view = View::new([
		'template' => 'My_Module.signup_form',
		'route' => 'POST /signup', 
	]);

Views may also contain other views:

    $view = View::new([
		'template' => 'My_Module.signup_form',
		'route' => 'POST /signup', 
		'username' => View::new([
			'template' => 'My_Module.input_field',
		]), 
		'email' => View::new([
			'template' => 'My_Module.input_field',
		]), 
		'password' => View::new([
			'template' => 'My_Module.input_field',
		]), 
	]);

### Standard View Classes ###

Note that previous examples are a bit simplified. Actual code for this form uses standard `Form`, `Input` and `MenuBar` sub-classes of `View` class: 

	$view = Form::new([
        'modifier' => '-sign-up',
        'route' => 'POST /sign-up',
        'submitting_message' => m_("Signing up ..."),
        'views' => [
            'name' => Input::new([
                'name' => 'name',
                'title' => m_("Username"),
                'modifier' => '-filled',
                'required' => true,
                'comment' => m_("Unique user name used in book URL"),
            ]),
            'email' => Input::new([
                'name' => 'email',
                'title' => m_("Email"),
                'modifier' => '-filled',
                'required' => true,
            ]),
            'password' => Input::new([
                    'name' => 'password',
                    'title' => m_("Password"),
                    'modifier' => '-filled -password',
                    'required' => true,
            ]),
        ],
        'footer' => MenuBar::new([
            'modifier' => '-center',
            'items' => [
                'login' => [
                    'type' => Type::COMMAND,
                    'title' => m_("Sign Up"),
                    'modifier' => '-filled',
                ],
            ],
        ]),
    ]);

### Creating Custom View Classes ###

You may also create your own view classes. Custom view classes make code more readable as it is clear which properties view object may have. View classes are created in `Views` subnamespace:

	/**
	 * @property string $person_name @required @part
	 */
	class Sidebar extends View
	{
	    public $template = 'My_Module.sidebar';
	}

### View Templates ###

View template is a file with `blade.php` extension containing text and placeholders. Placeholders are replaced with actual data during rendering. 

Previous example already referenced non-existing template `'My_Module.sidebar'`. It reads as "use `sidebar.blade.php` template file from `My_Module` module".   

Create view template in [area resource directory](#) `[my_module_path]/frontend/views/sidebar.blade.php` and put template text into it:

    <p>Welcome, guest!</p>

You can use placeholders for dynamic data. While rendering, there is always defined `$view` variable containing a reference to view object, so it is easy to render view properties in its template:

	<p>Welcome, {{ $view->person_name }}!</p>  

View templates are described in detail in [Laravel documentation](https://laravel.com/docs/blade).

### View Rendering ###

To render a view, just convert view object to string:

    use Manadev\Framework\Views\View;

    $view = Sidebar::new();
    $html = (string)$view;

Make sure that every rendered view's `template` property is assigned, either when invoking view class constructor or in class definition.

## Layers ##

Layer is just a PHP file containing instructions for creating and modifying layout. 

### Example ###

Create layer in [area resource directory](#) `[my_module_path]/frontend/layers/my_layer.php` and layer instructions into it. Below it layer file for a page containing sign-up form:

	<?php
	
	use Manadev\Framework\Views\View;
	use Manadev\Framework\Views\Views\Container;
	use Manadev\Ui\Forms\Views\Form;
	use Manadev\Ui\Inputs\Views\Input;
	use Manadev\Ui\MenuBars\Views\MenuBar;
	use Manadev\Ui\Menus\Items\Type;

	return [
	    '@include' => ['base'],
	    '#page' => [
	        'modifier' => '-home',
	        'content' => Container::new([
	            'id' => 'content',
	            'views' => [
	                'intro' => View::new(['template' => 'Manadev_DocHost_App.home_page_intro']),
	                'sign_up_form' => Form::new([
	                    'modifier' => '-sign-up',
	                    'route' => 'POST /sign-up',
	                    'submitting_message' => m_("Signing up ..."),
	                    'views' => [
	                        'name' => Input::new([
	                            'name' => 'name',
	                            'title' => m_("Username"),
	                            'modifier' => '-filled',
	                            'required' => true,
	                            'comment' => m_("Unique user name used in book URL"),
	                        ]),
	                        'email' => Input::new([
	                            'name' => 'email',
	                            'title' => m_("Email"),
	                            'modifier' => '-filled',
	                            'required' => true,
	                        ]),
	                        'password' => Input::new([
	                                'name' => 'password',
	                                'title' => m_("Password"),
	                                'modifier' => '-filled -password',
	                                'required' => true,
	                        ]),
	                    ],
	                    'footer' => MenuBar::new([
	                        'modifier' => '-center',
	                        'items' => [
	                            'login' => [
	                                'type' => Type::COMMAND,
	                                'title' => m_("Sign Up"),
	                                'modifier' => '-filled',
	                            ],
	                        ],
	                    ]),
	                ]),
	            ],
	        ]),
	        'translations' => [
	            "Signed up successfully." => "Signed up successfully.",
	        ],
	    ],
	];

### Layout Instructions ###

Layer file returns array of layout instructions:

	<?php

	return [
		'instruction1' => [ /* parameters */ ],
		...
		'instructionN' => [ /* parameters */ ],
	];

2 main types of instructions are **include** and **modify**.

Include other layers using `@include` directive:

	return [
	    '@include' => ['base'],
		...
	];

Modify views by using `#view_selector` syntax. The following example finds view with id `page` and changes its `modifier` property:

	return [
		...
	    '#page' => [
	        'modifier' => '-home',
	    ],
	];

Selector always starts with `#` followed by view unique identifier assigned to its `id` property. You can also add one or more `.property` or `.array_property[key]` clauses to reference child view which is assigned to some property or to array property element. 

The following example finds view having unique identifier `form` and then modifies its child view stored in `title` key of `views` array property:

	return [
		...
        '#form.views[title]' => [
            'value' => $account->display_name,
        ],
	];


### Rendering Layers ###

You can render layout defined in the layers using `m_layout()` function:

	class Frontend extends Controller {
		public function signUpPage() {
			return m_layout('signup');
		}
	} 

### Page Layers ###

It is good practice to create different layer for every controller method rendering full page. All page type specific layers (or just **page layers**) should include `page` layer.

By convention, page layer name should reflect URL of the page. For example, invoicing application having `/login` page, `/invoices` list page and `/invoice` invoice entry page should define `login`, `invoices` and `invoice` layers, for each page type:

	// login.php
	return [
	    '@include' => ['page'],
		// add login form
	];

	// invoices.php
	return [
	    '@include' => ['page'],
		// add invoice list
	];

	// invoice.php
	return [
	    '@include' => ['page'],
		// add invoice editing form
	];

### `page` And `base` Layers ###

by default, `page` layer just includes `base` layer and does nothing more than that:

	// vendor/dubysa/framework/src/Framework/Layers/resources/layers/page.php
	return [
	    '@include' => ['base'],
	];

`base` layer, simply put, tells to render the page using standard `Page` view:

	// vendor/dubysa/framework/src/Framework/Layers/resources/layers/base.php
	use Manadev\Framework\Views\Views\Page;
	
	return [
	    'root' => Page::new(),
	]; 

`Page` view contains many useful properties which you can customize for every page, such as:

* `title` - page meta title
* `content` - visual content of the page
* `header` - page header, rendered above the content
* `footer` - page footer, rendered above the content
* `translations` - array of string translations to be used in JavaScript code
* and more

It is important to understand that when you include `base` layer, you actually include `base.php` files of all the modules. Many modules (mostly the ones defining standard UI components) add their additional layer instructions to `base` layer. Your application may also add something to `base` layer which should be rendered on every single page.

At this moment you may wonder why there are 2 different layers, `base` and `page` which are basically the same. 

The idea here is most pages in your application would contain the same page layout (same header, footer and basic HTML layout). Include `page` layer in such pages and and add common header footer and other visuals to `page` layer.  

Still, some pages you may want to render "from scratch" - no header, no footer, no HTML structural elements. include `base` layer in such pages.  


### Layer Hierarchy ###

For simple application having 3 level hierarchy (page layers including `page` layer which in turn includes `base` layer) is enough. In more complex applications, however, you may consider adding more intermediate levels to this hierarchy.

For instance, account pages of e-commerce application may share the same sidebar containing links to all account pages, so it makes sense to add sidebar view to `my_account` layer and include it in `account_details` and `my_orders` page layers:

	// my_account.php
	return [
	    '@include' => ['page'],
		// add sidebar containing links to all account pages
	];

	// account_details.php
	return [
	    '@include' => ['my_account'],
		// add account detail editing form
	];

	// my_orders.php
	return [
	    '@include' => ['my_account'],
		// add list of orders
	];

### Separating Design And Data ###

Another good practice is to specify visual design in layer files and then inject data into it in controller method.  

For example:

	// Frontend.php - controller methos showing account detail editing page
    public function accountDetailsPage() {
		// load account data
		$account = $this->accounts->load();

		// load the design of account page from layer file and then inject data into it
        return m_layout('account_details', [
            '#page' => [
                'title' => $account->username,
            ],
            '#form.views[name]' => [
                'value' => $account->username,
            ],
            '#form.views[title]' => [
                'value' => $account->display_name,
            ],
        ]);
    }

	// account_details.php
	return [
	    '@include' => ['my_account'],
	    '#page' => [
	        'content' => Form::new([
                'id' => 'form',
                'route' => 'POST /account/details',
                'submitting_message' => m_("Saving changes to your account..."),
                'views' => [
                    'title' => Input::new([
                        'name' => 'title',
                        'title' => m_("Display Name"),
                        'modifier' => '-filled',
                        'required' => true,
                    ]),
                    'name' => Input::new([
                        'name' => 'name',
                        'title' => m_("Internal User Name"),
                        'modifier' => '-filled',
                        'required' => true,
                    ]),
                ],
            ]),
		],
	];