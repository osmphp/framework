# Command Line Aliases

{{ toc }}

### meta.abstract

Install and use `osm`, `osmc`, `osmt` and `osmh` *command line aliases* for faster command typing.

## Overview

Use *command line aliases* for faster command typing. For example, after installing command line aliases, you can compile the application using

    osmc Osm_App

instead of verbose 

    php vendor/osmphp/core/bin/compile.php Osm_App

## Installation

### Linux

Add the following lines to the end of your `~/.bashrc` file:

    alias osmc='php vendor/osmphp/core/bin/compile.php'
    alias osmh='php vendor/osmphp/core/bin/hint.php'
    alias osmt='php vendor/osmphp/framework/bin/tools.php'
    alias osm='php vendor/osmphp/framework/bin/console.php'

### Windows

1. Create a directory, for example `C:\osm` and extract all the files from the [osmphp/windows-aliases](https://github.com/osmphp/windows-aliases/archive/refs/heads/v0.1.zip) Git repository.
2. Add `C:\osm\windows-aliases-0.1` directory to the `PATH` system environment variable:

    1. Press Windows button to open the Windows Start menu.
    2. Type `env`, and open `Edit the system environment variables` window.
    3. Click the `Environment variables` button.
    4. In the `System variables` (the second one) section, double-click the `Path` variable.
    5. Using the `New` button, add `C:\osm\windows-aliases-0.1` directory.
    6. Click `OK` button in all three opened modal windows. 

## Usage

### `osm` - Main Application

Use `osm` alias for running commands of the main application, for example, for running the database migrations:

    osm migrate:up
    
Explore all the commands by running `osm` alias without parameters:

    osm 

Explore arguments and options of a specific command using `-h` switch:
     
    osm migrate:up -h
    
### `osmc` - Compiler

Use `osmc` alias for compiling an application. For example, compiling the main application:

    osmc Osm_App
    
### `osmh` - Hint Generator

Use `osmh` alias for generating a hint files that enable better navigation and code completion in PhpStorm:

    osmh Osm_App
   
### `osmt` - Tools

Use `osmt` alias for running other command-line tools, for instance, for generating boilerplate code. 

Just as with `osm`, explore its commands, their arguments and options by running

    osmt
    
  