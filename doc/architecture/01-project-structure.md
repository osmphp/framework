# Project structure #

{{ toc }}

Osm [installs](../installation.html) all necessary files and directories into project directory `{project_dir}` 

## Project Directories And Files

- **`app/`**  - application specific directories and files
  - **`app/docs/`** - application documentation 
  - **`app/samples/`** - application test related source code 
  - **`app/src/`** - application source code, organized as one or several [modules](modules.html)
  - **`app/tests/`** - application tests
- **`config/`** - application configuration directory. Used to add new configuration parameters or overwrite default module configuration
- **`data/`** - directory to store data, saved as flat files
- **`node_modules/`** - installed `npm` packages 
- **`public/`** - publicly accessible web application root directory. 
Stores request entry point as well as asset files (CSS, JavaScript, images, fonts, etc.) used to render web page for production environments
  - **`public/development/`** - development environment asset directory
  - **`public/testing/`** - testing environment asset directory
  - **`public/index.php`** - entry point for all HTTP requests
  - **`public/.htaccess`** - Apache configuration file. It will be used if project is hosted under Apache and ignored otherwise
- **`temp/`** - directory is used to store application temporary and cache files, compiled layers and templates, user session information and log files
  - **`temp/development/`** - development environment temp files
  - **`temp/testing/`** - testing environment temp files
- **`vendor/`** - installed `Composer` packages, including `Osm` framework
- **`.componentignore`** - the file is automatically generated and should not be modified manually
- **`.env`** - important configuration items such as your app is running in your local development, testing or production environment
- **`.env.testing`** - configuration used for test environment. It will be used in case `APP_ENV=testing` is set 
in `.env` file, or when application gets `env=testing` parameter
- **`.gitignore`** - declare all files and directories to be ignored by Git
- **`bootstrap.php`** - common file, which is executed before every HTTP request and every CLI command. Should contain as less source as possible 
- **`composer.json`** - the configuration file, that specifies all of Composer dependencies
Most important is `require` section, then `autoload`
- **`composer.lock`** - takes all of the dependencies you pulled in and lock them this specific versions 
This ensure project development consistency. If you share this project with somebody else, 
he will get the identical versions to you. This file should be committed to your version control
The file is automatically generated and should not be modified manually
- **`fresh`** - Osm CLI command to refresh project cache 
- **`package.json`** - stores Node package dependencies, that will be installed through `npm` and will be referenced in your JavaScript
The file is generated automatically and should not be modified manually
- **`package-lock.json`** - automatically generated file containing exact currently installed version of each Node package
The file should not be modified manually
- **`readme.md`** - Markdown description of your project. It will be automatically shown in project repository 
in BitBucket or GitHub.
- **`run`** - entry point for application command line interface

## Git Version Control Settings For Project Directories And Files

After project is installed, it is already ready for Git. You can create own Git repository to track project changes. 

However, there are a lot of files, that cannot be directly modified in the project, 
therefore it is not needed to track the changes and store them under Git.
Git repository should have only application specific files and directories. 
For instance, `mpm` and `Composer` packages will be updated from external repositories, 
while some project directories and files related only to current machine.

Main `.gitignore` file, located in root project directory, sets up the list of directories and root files, 
which should be ignored by GIT. 

Some directories as well contains `.gitignore` files:

- `config/` 
- `data/` 
- `public/`
- `temp/`

To explicitly state that `app/src/` directory should be in GIT repo, `app/src/.gitkeep` file exists.  

>**NOTE:** It is important to keep installed `.gitkeep` and `.gitignore` files, 
although the content of `.gitignore` files can be carefully modified.







