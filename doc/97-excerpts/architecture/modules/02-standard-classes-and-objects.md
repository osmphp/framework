# Standard classes and objects #

{{ toc }}

## CollectionRegistry    

Class to get configuration settings.

Osm reads configuration from all modules together with project configuration 
from `config` directory and merge it to one array.

Default `CollectionRegistry` behavior is to convert values to objects 

To use this class, ... has `$config_` property should be set 
name of the class to create every element fro array...

## $osm_app ##

Global application variable stores current top level application object of `Osm\Core\App` class. 

Application object contains all other objects.

Variable definition:

    global $osm_app; /* @var App $osm_app */
    