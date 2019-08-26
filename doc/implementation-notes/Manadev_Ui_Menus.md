# `Osm_UI_Menus` #

{{ toc }}

## About This Module ##

Osm has 2 visually different, yet structurally identical UI components:

* popup menus, implemented in [`Osm_UI_PopupMenus` module](Osm_UI_PopupMenus.html)
* menu bars, implemented in [`Osm_UI_MenuBars` module](Osm_UI_MenuBars.html)

This module provides implements common parts of both these UI components.

Keeping same configuration and similar PHP/JS API for popup menus and menus bars is useful for:

* code consistency - once you learn menu bars, using popup menus is a no brainer
* implementing mobile-friendly menu bars - on mobile it transforms into a button with popup menu and it is natural to handle desktop menu bar events and mobile popup menu events in the same way

## Registries ##

### Menu Item Types ###

`$osm_app->modules['Osm_Ui_Menus']->item_types` is a registry of all types of menu items which can be added to menu bars or popup menus.

This registry is used internally by menu bar and popup menu views. When instantiating the menu bar or popup menu view, you list the items to be shown in menu and among other things specify the type of each menu item. Based on type of the item, the view knows how to prepare item data for display and how to display each item.

Use this registry to define new menu item types.

This registry works over `config/menu_item_types.php` files:

* common characteristics are defined in `vendor/osmphp/framework/src/Ui/Menus/config/menu_item_types.php`
* menu bar specific properties are added to the types in `vendor/osmphp/framework/src/Ui/MenuBars/config/menu_item_types.php`
* popup menu specific properties are added to the types in `vendor/osmphp/framework/src/Ui/PopupMenus/config/menu_item_types.php`

When loaded, entries are converted into instances of `Osm\Ui\Menus\Items\Type` class:

	/**
	 * @property string $name @required @part
	 * @property string $model_class @required @part
	 *
	 * @see \Osm\Ui\MenuBars\Module
	 *      @property string $menu_bar_template @required @part
	 * @see \Osm\Ui\PopupMenus\Module
	 *      @property string $popup_menu_template @required @part
	 */
	class Type extends Object_
	{
		...
	}

## Base Classes ##

### Menu View ###

`Osm\Ui\Menus\Views\Menu` is a base class for menu bar and popup menu views. It contains common properties and methods for defining and displaying menu items.



### Menu Item ###

