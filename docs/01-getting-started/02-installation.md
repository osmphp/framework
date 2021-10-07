# Installation

Once the PHP 8, Node and Gulp are installed, create new
projects quickly using the command line.

Contents:

{{ toc }}

## meta.list_text

Once PHP 8, Node and Gulp are installed, create new projects quickly using the command line.

## Prerequisites

Before you begin, install the following prerequisites:

* [PHP 8 or later](https://www.php.net/manual/en/install.php), and enable `curl`, `fileinfo`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `pdo_sqlite`, `sqlite3`
  extensions
* [Node.js, the latest LTS version](https://nodejs.org/en/download/current/)
* [Gulp 4 command line utility](https://gulpjs.com/docs/en/getting-started/quick-start#install-the-gulp-command-line-utility) 
* [Osm Framework command line aliases](10-framework-command-line-aliases.md)  

## Creating A Project

Run the following commands:

    # download project files into `project1` directory, 
    # and install PHP dependencies 
    composer create-project osmphp/project project1
    
    # make it the current directory
    cd project1

    # run the installation
    bash bin/install.sh
    
On Windows, instead of the last line run the following commands:

    # compile the applications
    osmc Osm_App
    osmc Osm_Project
    osmc Osm_Tools

    # collect JS dependencies from all installed modules
    osmt config:npm
        
    # install JS dependencies
    npm install
    
    # build JS, CSS and other assets
    gulp

**Note**. Some commands may show no output. Don't worry - it means they worked as expected :)

After creating a project, check that it works in the command line:

    osm

## Using PHP Web Server

The easiest way to try out the application is to use the Web server that is bundled with PHP.

Start the native PHP Web Server in the project directory:
    
    # start the Web application on the `8000` port
    php -S 0.0.0.0:8000 -t public/Osm_App public/Osm_App/router.php
    
While the Web server is running, open the application home page in a browser: <http://127.0.0.1:8000/>.

## Using `gulp watch`

That's all - you can begin tinkering project files!

However, before you do, run the following command in the project directory:

    gulp watch
    
Keep this command running as long as you change the project files. It detects file changes, and automatically: 

* recompiles the application,
* rebuilds JS, CSS and other assets.     

In some cases, you may need to restart this command.