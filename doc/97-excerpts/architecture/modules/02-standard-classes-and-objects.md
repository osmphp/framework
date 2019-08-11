# Standard classes and objects #

{{ toc }}

## CollectionRegistry    

Class to get configuration settings.

Dubysa reads configuration from all modules together with project configuration 
from `config` directory and merge it to one array.

Default `CollectionRegistry` behavior is to convert values to objects 

To use this class, ... has `$config_` property should be set 
name of the class to create every element fro array...

## $m_app ##

Global application variable stores current top level application object of `Manadev\Core\App` class. 

Application object contains all other objects.

Variable definition:

    global $m_app; /* @var App $m_app */
    