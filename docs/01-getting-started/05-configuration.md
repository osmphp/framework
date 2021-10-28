# Configuration

Configure the project settings, such as language, theme, database and search index connection details, logging, and other, in the `settings.{app}.php` file. Put machine-specific settings into the `.env.{app}` file. Copy and adapt ready-to-use examples from this document.

Contents:

{{ toc }}

### meta.abstract

Configure the project settings, such as language, theme, database and search index connection details, logging, and other, in the `settings.{app}.php` file. Put machine-specific settings into the `.env.{app}` file. Copy and adapt ready-to-use examples from this document.

## About Settings And Environment Variables

### Default Values

Settings come with sensible defaults that are defined by the application modules. For example, if not explicitly configured, applications operate using the default `en_US` locale. 

That's why, initially, the `settings.php` file is empty:

    <?php
    
    declare(strict_types=1);
    
    /* @see \Osm\Framework\Settings\Hints\Settings */
    return (object)[
        // common settings
    ]; 

### Application-Specific Settings
  
The project contains several applications: the main application, `Osm_App`, the sample application used for testing purposes, `My_Samples`, and the tool application that helps to manage the project and write code faster, `Osm_Tools`. Applications may have different settings, for example, the main application may be configured to use MySql while the sample application my use SQLite database engine. Put the application-specific settings into the `settings.{app}.php` files, for example, put the settings specific to the main application into the `settings.Osm_App.php` file.

### Machine-Specific Settings (Environment Variables)

Settings are the same on a developer's machine and on a production server. Use global `$_ENV` variable to inject machine-specific details, such as host names, users, passwords, etc. 

`$_ENV` global, used for machine-specific settings, is filled in from the environment variables defined in the operating system (`EXPORT` command in Linux, `SET` command in Windows).

`$_ENV` is also filled in from `.env.{app}` file. This way, the main application environment variables are defined in the `.env.Osm_App` file, and the environment variables of the sample application are defined in the `.env.My_Samples` file.

Unlike setting files, the `.env.{app}` files are not under version control. It means that they are different on the development machine, and on the production server.

### Custom Settings

The rest of this document lists the settings and environment variables used to configure how Osm Framework works. If needed, you can introduce your own [custom settings](../07-other-features/03-configuration.md).

## Standard Settings And Environment Variables

This section provides ready-to-use examples of the standard Osm Framework settings. 

You can also inspect all the settings defined and used by the application by running `osmh` command, and then checking the definition of the `Osm\Framework\Settings\Hints\Settings` class in `generated/hints.php` file.

### Locale/Language

By default, an application operates using locale/language defined in the `LOCALE` environment variable in the `.env.Osm_App` file: 

    LOCALE=lt_LT

If omitted, `en_US` locale is used. You may override this behavior in the `settings.php` file:

    ...
    return (object)[
        ...
        'locale' => 'lt_LT',
    ]; 
 
By convention, locale name follows `{language}_{COUNTRY}` format.

### Theme

An application may have several *areas*. Typically, `front` area is for website visitors, and `admin` area is website administrators. Each application area is rendered using `_{area}_{theme}` theme. 

By default, the `{theme}` name is defined in the `THEME` environment variable in the `.env.Osm_App` file: 

    THEME=my

If omitted, `tailwind` theme name is used. You may override this behavior in the `settings.php` file: 

    ...
    return (object)[
        ...
        'theme' => 'my',
    ]; 

### Application Name

Use the same name as the database an application uses, as a search index prefix. Define this shared name in the `NAME` environment variable, and use it in other environment variables in the `.env.Osm_App` file:  

    NAME=project1
    ...
    MYSQL_DATABASE="${NAME}"
    ...    
    SEARCH_INDEX_PREFIX="${NAME}_"

### Database

Osm Framework uses Laravel for dealing with the databases. Laravel supports MySql, PostgreSQL, SQLite and SQLServer database engines, and this section provides configuration examples. 

For more advanced configuration, such as separating read and write connections, or using a database cluster, check [Laravel documentation](https://laravel.com/docs/database). 

#### MySql

First, define the database configuration in the `settings.php` file:

    ...
    return (object)[
        ...
        'db' => [
            'driver' => 'mysql',
            'url' => $_ENV['MYSQL_DATABASE_URL'] ?? null,
            'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['MYSQL_PORT'] ?? '3306',
            'database' => $_ENV['MYSQL_DATABASE'],
            'username' => $_ENV['MYSQL_USERNAME'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'unix_socket' => $_ENV['MYSQL_SOCKET'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => $_ENV['MYSQL_ATTR_SSL_CA'] ?? null,
            ]) : [],
        ],
    ]; 

After that, define mentioned environment variables in the `.env.Osm_App` file: 

    NAME=project1
    ...
    MYSQL_DATABASE="${NAME}"
    MYSQL_USERNAME=...
    MYSQL_PASSWORD=...

#### SQLite File

First, define the database configuration in the `settings.php` file:

    global $osm_app; /* @var \Osm\Core\App $osm_app */
    ...
    return (object)[
        ...
        'db' => [
            'driver' => 'sqlite',
            'database' => "{$osm_app->paths->temp}/db.sqlite',  
            'prefix' => '',
            'foreign_key_constraints' => $_ENV['SQLITE_FOREIGN_KEYS'] ?? true,
        ],
    ]; 

Optionally, define mentioned environment variables in the `.env.Osm_App` file.

#### SQLite In Memory

This option is especially handy in unit testing.

Define the database configuration in the `settings.php` file:

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

### Search

Osm Framework supports ElasticSearch, and Algolia search engines. 

#### ElasticSearch

First, define the search index configuration in the `settings.php` file:

    'search' => [
        'driver' => 'elastic',
        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
        'hosts' => [
            $_ENV['ELASTIC_HOST'] ?? 'localhost:9200',
        ],
        'retries' => 2,
    ],

After that, define mentioned environment variables in the `.env.Osm_App` file:

    NAME=project1
    ...
    SEARCH_INDEX_PREFIX="${NAME}_"

Most ElasticSearch queries are executed asynchronously. It means that calling PHP code continues execution not waiting the query to complete. Disable this behavior in unit tests by modifying the configuration of the sample application in the `settings.My_Samples.php` file:

    ...
    return \Osm\merge((object)[
        'search' => [
            'refresh' => true, // index new data immediately
        ],
    ], include __DIR__ . '/settings.php'); 

#### Algolia

First, define the search index configuration in the `settings.php` file:

    'search' => [
        'driver' => 'algolia',
        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
        'app_id' => $_ENV['ALGOLIA_APP_ID'],
        'admin_api_key' => $_ENV['ALGOLIA_ADMIN_API_KEY'],
    ],

After that, define mentioned environment variables in the `.env.Osm_App` file:

    NAME=project1
    ...
    SEARCH_INDEX_PREFIX="${NAME}_"
    ALGOLIA_APP_ID=...
    ALGOLIA_ADMIN_API_KEY=...

Most Algolia queries are executed asynchronously. It means that calling PHP code continues execution not waiting the query to complete. Disable this behavior in unit tests by modifying the configuration of the sample application in the `settings.My_Samples.php` file:

    ...
    return \Osm\merge((object)[
        'search' => [
            'wait' => true, // index new data immediately
        ],
    ], include __DIR__ . '/settings.php'); 

### Logging

Some logs are always enabled, for example, HTTP errors are always logged. Other logs are disabled by default, and can be enabled if needed in the `.env.Osm_App` file:

    LOG_DB=true
    LOG_ELASTIC=true

You may override this behavior in the `settings.php` file:

    ...
    return (object)[
        ...
        /* @see \Osm\Framework\Logs\Hints\LogSettings */
        'logs' => (object)[
            'elastic' => true,
            'db' => true,
        ],
    ]; 

## Gulp

`gulp` and `gulp watch` commands make development faster, as they detect file changes and perform mundane tasks instead of you: 

* recompile the application,
* clear the application cache,
* rebuild JS, CSS, Blade templates, and other assets.    

These commands are configured in the `gulpfile.js` file:

    // In the global configuration object, keys are application names to be
    // compiled, and values are arrays of theme names to build for that application
    global.config = {
        'Osm_Tools': [],
        'Osm_Project': [],
        'Osm_App': ['_front__tailwind']
    };
    
    // Run the framework Gulp scripts that define all the Gulp tasks, and
    // export these tasks to the Gulp runner
    Object.assign(exports, require('./vendor/osmphp/framework/gulp/main'));

In the `global.config` map object, keys are application names to be recompiled, and values are arrays of theme names to be rebuilt.  

