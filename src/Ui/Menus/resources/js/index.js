import macaw from 'Osm_Framework_Js/vars/macaw';
import PopupMenu from './PopupMenu/Menu';
import PopupCommandItem from './PopupMenu/CommandItem';
import PopupLinkItem from './PopupMenu/LinkItem';
import PopupCheckboxItem from './PopupMenu/CheckboxItem';

macaw.controller(Osm_Ui_Menus.PopupMenu.Menu, PopupMenu);
macaw.controller(Osm_Ui_Menus.PopupMenu.CommandItem, PopupCommandItem);
macaw.controller(Osm_Ui_Menus.PopupMenu.LinkItem, PopupLinkItem);
macaw.controller(Osm_Ui_Menus.PopupMenu.CheckboxItem, PopupCheckboxItem);
