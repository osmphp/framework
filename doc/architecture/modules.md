# Modules #

Dubysa application is made of modules. Each module handles relatively simple task with well-defined boundaries. 

Examples: 

* `Manadev_Framework_Http` module handles HTTP requests
* `Manadev_Framework_WebPack` module handles integration with WebPack
* `Manadev_Framework_Migrations` module executes database preparation scripts
* `Manadev_Ui_PopupMenus` modules allows displaying popup menus on pages

Dubysa **module** is a directory which contains PHP, JavaScript, CSS and other files. 

Dividing monolithic application into relatively simple modules makes it easier to understand, maintain, update and customize.

You can build quite different applications just by combining different set of modules into them and adding your own modules only for application-specific functionality. 