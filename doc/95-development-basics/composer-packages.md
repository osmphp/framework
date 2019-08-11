# Composer packages
Composer is a tool for managing dependencies. Composer enables you to declare PHP libraries from different vendors you depend on.

At the same time Composer tracks the version of all declared packages and automatically downloads, installs or updates them if needed.

Thus Composer packages support solution re-usability  in many different projects.

Example of Composer packages in `composer.json` 

    "require": {
        "php": ">=7.1.0",
        "dubysa/components": "dev-master@dev"
    },

Composer checks and download package into `vendor` directory. After downloading the package Composer checks   required composer packages in `vendor/dubysa/components/composer.json` :

    "require": {
        "php": ">=7.1",
        "vlucas/phpdotenv": "~2.2",
        "nikic/php-parser": "^4.0",
        "laravel/framework": "5.7.*",
        "symfony/dom-crawler": "~3.3",
        "doctrine/dbal": "~2.5"
    },

Composer downloads all required packages and checks `composer.json` located in every downloaded package directory. Composer repeats this recursively until all required packages are downloaded.

## Vendor package structure 

All packages are stored under `vendor` directory in two layers structure `package-owner` / `package-name`

Package modules are described in package `composer.json` :

    "extra": {
        "dubysa": {
            "component_pools": {
                "src": {
                    "module_path": "*/*/Module.php",
                    "theme_path": "*/*/theme.php"
                },
                "samples": {
                    "module_path": "*/Module.php",
                    "testing": true
                }
            }
        }
    }

means that in `src` directory modules and themes can be stored. Modules can be found `Module.php` located in third level directory starting from package directory. Each theme should have `theme.php` file in third level directory starting from package directory.

`samples` are components used for unit testing. Each sample can be found in second level directory in `Module.php`.
Samples can be loaded only if testing mode is on : `"testing": true`

## Composer package autoload in Dubysa

When Dubysa console project is started by running `php run` in command line, `run` file located in project root directory is executed.

`require $dir . '/vendor/autoload.php';` command prepares Composer package loading from `vendor` directory. 
Composer reads `composer.json` from every `vendor` package directory and loads packages.

For example `vendor/dubysa/components/composer.json` autoload section

    "autoload": {
        "files": [
            "src/helpers.php"
        ],
        "psr-4": {
            "Manadev\\": "src/",
            "Manadev\\Tests\\": "tests/",
            "Manadev\\Samples\\": "samples/"
        }
    },
means that in `vendor/dubysa/components/src` all files are stored with `Manadev` namespace prefix.
When some class with `Manadev` namespace prefix will be used, it will be searched in `vendor/dubysa/components/src`  directory.

So `Manadev\Core\Object_` class will be found in `vendor/dubysa/components/src/Core/Object_.php`.

## `composer update`
If you want to get newest versions of composer packages open command line, switch to Dubysa project directory and run 
`composer update`.

It will check if newer version exists, download all needed files and install to project directory.
After all packages are updated it will 
- optimize class autoload to improve loading performance
- refresh project cache
- run Dubysa migration
- run configuration
- install NPM - Node.js package manager
- run NPM webpack - compile JS and CSS

