# Testing

Write unit tests. They are additional work, but it's worth it. Osm Framework comes all prepared for writing pure PHP tests, database tests, search tests, functional tests, tests using headless browser. Also, consider running the test suite after every push.

Contents:

{{ toc }}

### meta.abstract

Write unit tests. They are additional work, but it's worth it. Osm Framework comes all prepared for writing pure PHP tests, database tests, search tests, functional tests, tests using headless browser. Also, consider running the test suite after every push.

## Writing And Running Tests

While there are several great PHP testing frameworks out there, consider using [PHPUnit](https://phpunit.de/). Run it from the IDE, or from the command line in your project directory:

    vendor/bin/phpunit 

PHPUnit runs according to the `phpunit.xml` configuration file. Before running the test suite, it executes the `tests/bootstrap.php` file, and then it runs all test files in the `tests/` directory, that is, the ones that start with `test_`. Initially, there is a single test file, `tests/test_01_hello.php`:  

    ...
    class test_01_hello extends TestCase
    {
        public string $app_class_name = \My\Samples\App::class;
    
        public function test_app_name() {
            // GIVEN an app
    
            // WHEN you check its name
            $name = $this->app->name;
    
            // THEN it's the sample application's name
            $this->assertEquals('My_Samples', $name);
        }
    }

It's important to understand that the every test method (starting with `test`) is executed in the context of the sample application, `My_Samples`. It means that before each test, the application is loaded, and after each test, the application is destroyed.

The [sample application](04-application.md#sample-application-my-samples-app) is configured to include all modules of the [main application](04-application.md#main-application-osm-app-app), and, in addition, modules from the `samples/` directory, that define additional tables or classes used exclusively in unit tests. 

## Bootstrapping Test Suite

By default, `tests/bootstrap.php`, compiles the sample application and clears its cache:
    
    ...
    Apps::compile(App::class);
    Apps::run(Apps::create(App::class), function(App $app) {
        $app->cache->clear();
    });
    ...

If you use a database, or a search engine, modify the `bootstrap.php` to run migrations, and optionally seed some sample data:

    ...
    Apps::compile(App::class);
    Apps::run(Apps::create(App::class), function(App $app) {
        $app->cache->clear();
        
        $app->migrations()->fresh();
        $app->migrations()->up();
        
        // optionally, seed some sample data
    });
    ...

## Configuring Sample Application

Edit [application settings](../01-getting-started/05-configuration.md), so that the configuration shared by the main and sample applications is defined in `settings.php`, the configuration, specific to the main application, goes to `settings.Osm_App.php`, and the configuration, specific to the sample application goes to `settings.My_Samples.php`. 

Define the environment variables of the sample application in `.env.My_Samples`, while keeping the environment variables of the main application in `.env.Osm_App`.

What settings and environment variables should differ?

### `NAME` Environment Variable

First, the `NAME` environment variable. If, let's say, the `NAME` of the main application is `project1`, then in the `.env.My_Samples` file, set the `NAME` of the sample application to `project1_test`:

    NAME=project1_test
    
This way, tests will run on a different database, and different search indexes, than the production code.

### `CACHE` Environment Variable

In the `.env.My_Samples` file, configure the cache to be stored in memory for faster test execution:  

    CACHE=Osm\Framework\Cache\Array_

### `db` Setting

In case your code uses database, in the `settings.My_Samples.php` file, configure the tests to run against in-memory SQLite database:

    return (object)[
        ...
        'db' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
            'options' => [
                // use the same in-memory database in all tests
                PDO::ATTR_PERSISTENT => true,
            ],
        ],
    ]; 

As to the main application, keep its `db` setting in the `settings.Osm_App.php` file rather than in the `settings.php` file.

### `search` Setting

If your code uses search indexes, then in the `settings.My_Samples.php` file, force the search engine to refresh indexes after each operation. 

If using ElasticSearch:

    'search' => [
        'refresh' => true, // index new data immediately
    ],

If using Algolia:

    'search' => [
        'wait' => true, // index new data immediately
    ],

## Testing Database

If your tests touch the database, it's important that each test method leaves the database unchanged. In order to do that, set the `use_db` flag of the test class:

    ...
    class test_01_hello extends TestCase
    {
        public bool $use_db = true;
        ...
    }

When the `use_db` is set, PHPUnit starts a transaction before running each test, and rolls it back after the test finishes. 

## Testing Search

If your tests touch search indexes, again, it's important, that every test method leaves them unchanged. Either undo every operation that modifies a search index manually, or reindex it anew:

    class test_01_hello extends TestCase
    {
        ...
        public function test_app_name() {
            try {
            }
            finally {
                // undo every operation that modifies a search index manually, 
                // or reindex it anew
            }
        }
    }

## Running Functional Tests

A functional test is a test that sends HTTP requests to your application, and inspects its responses.
 
In functional tests, set `use_http` flag:

    ...
    class test_01_hello extends TestCase
    {
        public bool $use_http = true;
        ...
    }

If `use_http` flag is set, PHPUnit starts an HTTP client that routes the issued requests directly into the matching route in the same process. It means that both unit test, and the application code are executed in `phpunit` process. It's faster than running a real browser and sending requests and responses over the network. You can also use `use_db` flag, and keep the database unchanged.

[Example](https://github.com/osmphp/framework/blob/HEAD/tests/Unit/test_09_http.php):

    class test_09_http extends TestCase
    {
        ...
        public bool $use_http = true;
    
        public function test_internal_browser() {
            // GIVEN an app with a `GET /test` route and a browser
    
            // WHEN you browse the route
            $text = $this->http->request('GET', '/test')
                ->filter('.test')
                ->text();
    
            // THEN its output is fetched
            $this->assertEquals('Hi', $text);
        }
    }

## Running Tests In Browser

If your functional test depends on executing page JavaScript, use headless Chrome browser.

[Example](https://github.com/osmphp/framework/blob/HEAD/tests/Unit/test_10_chrome.php):

    <?php
    
    declare(strict_types=1);
    
    namespace Osm\Framework\Tests\Unit;
    
    use Facebook\WebDriver\WebDriverBy;
    use Osm\Framework\Samples\App;
    use Osm\Runtime\Apps;
    use Symfony\Component\Panther\PantherTestCase;
    
    class test_10_chrome extends PantherTestCase
    {
        public function test_chrome() {
            $paths = Apps::paths(App::class);
            $client = static::createPantherClient([
                'webServerDir' => "{$paths->project}/public/{$paths->app_name}",
                'router' => "{$paths->project}/public/{$paths->app_name}/router.php",
            ]);
    
            $client->request('GET', '/test');
            $this->assertEquals('Hi', $client
                ->findElement(WebDriverBy::cssSelector('.test'))
                ->getText());
        }
    }

## Running Tests On GitHub
 
It's a good practice to run the test suite everytime you push your project to GitHub using a GitHub action.

For an example, see how the [GitHub action of Osm Framework is configured](https://github.com/osmphp/framework/blob/HEAD/.github/workflows/test.yml).

This GitHub action defines a matrix of all environment combinations to run the test suite on, specifically, using both MySql and SQLite database engines, and both ElasticSearch and Algolia search engines. For each combination, the GitHub action:

* Creates a virtual machine with Ubuntu operating system.
* Installs and configures all required software: PHP, MySql, ElasticSearch, and other.
* Clones the project, in this case, the `osmphp/framework` repository.
* Installs the project: compiles its applications, migrates the database, runs Gulp, and other.
* Executes unit tests.

