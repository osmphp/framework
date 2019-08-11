# Dubysa console commands #

{{ toc }}

## `composer create-project`

Used to create new project.

Choose or create directory where you develop all your projects.

If you are on Linux, let's assume it is `/projects`, for Windows it would be `c:\_projects`.

In command line first go to root project directory and run Composer command with like in example below:

    cd /projects
    composer create-project -n dubysa/dubysa new-project "--repository={\"type\":\"vcs\",\"url\":\"git@bitbucket.org:dubysa/dubysa.git\"}"

where

 - `-n` means suppress questions
 - `create-project` - Composer console command to create a new project
 - `dubysa/dubysa` - project template package name.   
 - `new-project` - new project name
 - `--repository` - instruction where to search project template package.  

 
During execution of this command,

 - project files are downloaded from repository and copied locally into `dubysa` subdirectory,
 - environment configuration files `.env` and `.env.testing` are created,
 - Composer downloads latest version of all dependent packages,
 - Dubysa post-update scripts are executed
 
## `php fresh` ##

Refreshes application cache.
    
## `npm run webpack` ##

Command to process and combine JS and CSS files and save result to project `public` directory.
The command as well copies `view` and `layer` files to `temp` cache directory. 

Should be executed from project directory:

    cd c:\_projects\dubysa-docs
    npm run webpack

Module client-side related files like layer or blade, CSS and Javascript in Dubysa should be store in `resources/` module subdirectory.

On the other hand it is more convenient to keep project client side files together.

For this we provide you a command for resource file synchronization. 

Open command line, switch to Dubysa project directory and run `npm run webpack`. 

This command reads resource files from every module and do processing:
- copies all Javascript file content into one file
- SASS files are compiled to CSS
- layers and views are copied to `\temp\` directory for current environment: development, testing or production.

When resource is changed it is necessary to repeat this command or you can watch all resources by using `npm run watch` command. 

For testing environment Dubysa also provide  `npm run testing-webpack`

## `npm run watch` ##

Command is very useful when developing web application. It helps to refresh PHP cache automatically when something is changed.
Command loads all resources. If any resource is changed, it refresh cache automatically.
Restart is needed to include new directory for monitoring change.

Should be executed from project directory:

    cd c:\_projects\dubysa-docs
    npm run watch

For testing environment Dubysa also provide  `npm run testing-watch`

## `php run config:webpack`

When reconfiguration of webpack is needed run `php run config:webpack` console command in Dubysa project directory.

After that watching can be started again.   

## `php run config:npm`

prepare configuration file for Node.js package manager. This command finds npm packages from all modules and save result 
to `/projects/docs/package.json`.

## `npm install` 

Run installation according configuration file and save all required libraries to `/projects/docs/node_modules`.

## `composer require` ##

Adds required composer package to the project. Optional parameter `--no-scripts` skips post-update Dubysa scripts
Should be launched from project directory. 

Example:

    composer require "michelf/php-markdown" --no-scripts
    

 