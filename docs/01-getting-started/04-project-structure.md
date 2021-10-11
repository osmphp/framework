# Project Structure

After you got a project up and running, you may notice that there is a lot of directories and files in the project directories. While you'll spend most of your time in `src/` and `themes/` directories, it's worth knowing what's all the other stuff is about.

Let's inspect it in more detail: 

{{ toc }}

### meta.abstract

After you got a project up and running, you may notice that there is a lot of directories and files in the project directories. While you'll spend most of your time in `src/` and `themes/` directories, it's worth knowing what's all the other stuff is about.

## Source Files 

Most creative work happens in two directories: `src/` and `themes/`.

### Modules (`src/`)

Create all your modules under `src/` directory, using `My\` namespace.

Out of the box, a new project comes with 2 modules:

* `\My\Base\Module` (named `base`) is a place to define things that you'll use in all the other project modules. Initially, the only thing it does is requiring all the framework module into the application.

        public static array $requires = [
            \Osm\Framework\All\Module::class,
        ];

* `\My\Welcome\Module` (named `welcome`) implements `GET /` route showing you a greeting message. This module is temporary. After you get started, delete its `src/Welcome/` directory.

### Theme Assets (`themes/`)

This directory contains project-specific Blade templates, CSS, JavaScript, images and other assets.

Osm Framework supports multiple areas (mostly, `front` and `admin`) and multiple visual themes in each area, and every area/theme combination has its own subdirectory. For example, `themes/_front__tailwind/` is a place for all assets of the `tailwind` theme (the default Osm Framework theme) in the `front` area (the public area of the website).

Initially, in the `themes/_front__tailwind/` directory:

* `osm_theme.json` marks the directory as a theme asset directory. You can also put theme-specific settings there.
* `views/welcome/home.blade.php` is a template used by the `welcome` module for rendering the welcome page on the `GET /` route.

## Configuration

### Settings (`settings.Osm_App.php`)

Edit settings of the main application in the `settings.Osm_App.php` file, and the settings of the sample application (the one used in unit tests) in `settings.My_Samples.php` file. Put the common settings for both applications into the `settings.php` file.

In settings, specify the database and search index connection details, logging settings, and other settings.

Setting files are typically the same on a developer's machine and on a production server. Yet, environment-specific details (hosts, users, passwords, etc.) differ, so use environment variables instead of hard-coded values. For example, while configuring MySql connection, leave MySql server details up to environment variables using `$_ENV` global:

    /* @see \Osm\Framework\Settings\Hints\Settings */
    return (object)[
        ...
        'db' => [
            'driver' => 'mysql',
            'url' => $_ENV['MYSQL_DATABASE_URL'] ?? null,
            'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['MYSQL_PORT'] ?? '3306',
            'database' => "{$_ENV['MYSQL_DATABASE']}",
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
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        ...
    ];

### Environment Variables (`.env.Osm_App`)
    
`$_ENV` is filled in from the environment variables defined in the operating system (`EXPORT` command in Linux, `SET` command in Windows). 

`$_ENV` is also filled in from `.env.{app}` file. This way, the main application environment variables are defined in the `.env.Osm_App` file, and the environment variables of the sample application are defined in the `.env.My_Samples` file.

Unlike setting files, the `.env.{app}` files are not under version control. It means that they are different on the development machine, and on the production server. 

Back to the MySql example, define `MYSQL_*` variables in the env file as follows:

    NAME=project1
    
    MYSQL_DATABASE="${NAME}"
    MYSQL_USERNAME=...
    MYSQL_PASSWORD=...

## Testing

### Configuration (`phpunit.xml`)

For unit testing, use PHPUnit, and configure it in the `phpunit.xml` file.

### Tests (`tests/`)

Put PHP unit tests into the `tests/` directory. Initially, this directory contains: 

* `bootstrap.php`, that is executed before the test suite, and
* `test_01_hello.php`, a simple unit test file.

### Sample Code (`samples/`) 

Some tests require some sample code, or sample data, that should not be a part of the main application. Put this code or data into modules of the sample application, and define these modules in the `samples/` directory using `My\Samples` namespace.

Initially, the `samples/` directory doesn't contain any sample modules. However, it defines the sample application class, `My\Samples\App`, in the `App.php` file. 

## Dependencies 

### PHP Dependencies (`composer.json`, `composer.lock`, `vendor/`)

Composer packages required by the project, source directory to PHP namespace mappings, and other important project metadata are defined in the `composer.json` file. 

Composer installs required packages into the `vendor/` directory, and describes what's actually installed in the `composer.lock` file.

### JS Dependencies (`package.json`, `package-lock.json`, `node_modules/`)

In a similar fashion, Node packages required by the project are defined in the `package.json` file. Unlike `composer.json`, don't edit this file manually. Instead, run `osmt config:npm` command in order to collect and merge this file from all the project modules.

NPM installs required packages into the `node_modules/` directory, and describes what's actually installed in the `package-lock.json` file.

## Public Files (`public/Osm_App/`)

Most project files are not accessible from the outside. The only exception to this rule are files in the `public/` directory.

### HTTP Entry Point (`index.php`)

Every browser request sent to the main application is handled by `public/Osm_App/index.php` file, known as *HTTP entry point*. In its essence, the HTTP entry point finds matching route class, executes it, and sends the response generated by the route object, back to the browser.

### HTTP Router (`router.php`)

You can run the main application, `Osm_App`, under the native PHP Web server using the following command:

    php -S 0.0.0.0:8000 -t public/Osm_App public/Osm_App/router.php 
    
This command uses the `public/Osm_App/router.php` file that basically, routes the incoming requests through the HTTP entry point, `public/Osm_App/index.php`.

The `router.php` file is only used by the native PHP Web server. It's not used in production.

### Published Assets (`_front__tailwind/`)

After running Gulp, CSS styles, Javascript, images and other public assets are collected from the theme asset directories, processed, and put into the `public/{theme}/` directory. For example, the public assets of the default `_front__tailwind` theme are collected into the `public/_front__tailwind/` directory.

## Generated Files (`generated/`)

Gulp not only publishes assets, it also re-compiles applications by running the compiler, `osmc`. 

### Generated Classes (`Osm_App/classes.php`)

The compiler applies dynamic traits to the original classes by generating subclasses that are instantiated instead of original classes. All these generated subclasses are put into the `generated/{app}/classes.php` file, and loaded before running the application. 

When you instantiate a class using `Object_::new()` syntax, a class from the `classes.php` file is instantiated instead.

### Reflection Information (`Osm_App/app.ser`)

The compiler also instantiates the application object with all its modules, adds extensive reflection information, and serializes it into the `generated/{app}/app.ser` file. Before running the application, it's unserialized from this file, and put into the `$osm_app` variable.  

### IDE Helper (`hints.php`)

`osmh` command creates `generated/hints.php` file. This file provides additional symbol information for the PhpStorm IDE. It makes reading and navigating code a lot easier.   

## Other

### Temporary Files (`temp/Osm_App/`)

Sessions, logs, cache, collected server-side theme assets, and other temporary files are stored in the `themp/{app}/` directory.

### Data Files (`data/`)

By convention, if your application reads data from flat files rather than the database, put these files into the `data/` directory.

### Shell scripts (`bin/`)

Put shell scripts into the `bin/` directory.