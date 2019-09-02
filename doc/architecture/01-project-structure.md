# Project structure #

{{ toc }}

Osm [installs](../installation.html) all necessary files and folders into project directory `{project_dir}` 

## Project Root Directory Files

Project root directory `{project_dir}` contains several configuration files:

- `.componentignore`
- `.env` - important configuration items such as your app is running in your local 
environment or production environment. This file will never be committed to your version control
- `.envtesting` - configuration used for test environment. It will be used in case `APP_ENV=testing` is set 
in `.env` file, or when application gets `env=testing` parameter. 
- `.gitignore` - declare all files and directories to be ignored by GIT
- `.bootstrap.php`
- `composer.json` - the configuration file, that specifies all of Composer dependencies. 
Most important is `require` section, then `autoload`
- `composer.lock `- takes all of the dependencies you pulled in and lock them this specific versions. 
This ensure project development consistency. If you share this project with somebody else, 
he will get the identical versions to you. This file should be committed to your version control
- `fresh` - Osm CLI command to refresh project cache 
- `package.json` - this is to help with your frontend compilation. 
These are dependencies that will be installed through Node and will be referenced in your JavaScript
- `package.json`
- `readme.md` - Markdown description of your project. It will be automatically shown in project repository 
in BitBucket or GitHub.
- `run` - Osm CLI command to run the application in command line 

## Project Directories

- `app` directory contains application specific source code, organized as one or several [modules](modules.html).
- `config`. Various application configuration are stored here. This directory usually is ignored by source version control.
- `data`
- `node_modules` 
- `public` directory contains `index.php` - the default page of the application. 
Application assets such as images, JavaScript, and CSS as well are stored here. 
JavaScript and CSS files are compiled and ready to be used.
- `temp` directory is used to store application cache files, compiled layers and views, user session information and log files.
- `vendor` is a directory where all composer dependencies, including Osm framework, will be installed.




