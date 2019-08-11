# Creating Application #

## Creating New Project

Choose or create a directory where you develop all your projects.

>**NOTE:** We assume your root project directory is `/projects` for Linux or `c:\projects` for Windows.

First, open command-line, go to the root project directory and run Composer command with like in the example below:

    cd /projects
    composer create-project -n dubysa/dubysa new-project "--repository={\"type\":\"vcs\",\"url\":\"git@bitbucket.org:dubysa/dubysa.git\"}"

where

 - `-n` means suppress questions
 - `create-project` - Composer console command to create a new project
 - `dubysa/dubysa` - project template package name.   
 - `new-project` - new project name
 - `--repository` - instruction where to search project template package.  
 
During command execution, 

 - project files are downloaded from the repository and copied locally into `<your_application_name>` subdirectory,
 - Composer downloads latest version of all dependent packages,
 - Dubysa post-update scripts are executed and environment is configured.
 
You can read more about how to [customize project creation](../../php-development/dubysa-console-commands.html#composer-create-project).

## Building Application

After project is created, you can start adding logic to new application.

In [our tutorials](../../tutorials.html) we have several step-by-step examples of developing real applications with Dubysa, getting acquainted with Dubysa development principles and concepts.

