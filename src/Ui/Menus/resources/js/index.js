import macaw from 'Osm_Framework_Js/vars/macaw';

import PopupMenu_Menu from './PopupMenu/Menu';
import PopupMenu_CommandItem from './PopupMenu/CommandItem';
import PopupMenu_LinkItem from './PopupMenu/LinkItem';
import PopupMenu_CheckboxItem from './PopupMenu/CheckboxItem';
import PopupMenu_SubmenuItem from './PopupMenu/SubmenuItem';

import MenuBar_Menu from './MenuBar/Menu';
import MenuBar_CommandItem from './MenuBar/CommandItem';
import MenuBar_LinkItem from './MenuBar/LinkItem';
import MenuBar_CheckboxItem from './MenuBar/CheckboxItem';
import MenuBar_SubmenuItem from './MenuBar/SubmenuItem';

macaw.controller(Osm_Ui_Menus.PopupMenu.Menu, PopupMenu_Menu);
macaw.controller(Osm_Ui_Menus.PopupMenu.CommandItem, PopupMenu_CommandItem);
macaw.controller(Osm_Ui_Menus.PopupMenu.LinkItem, PopupMenu_LinkItem);
macaw.controller(Osm_Ui_Menus.PopupMenu.CheckboxItem, PopupMenu_CheckboxItem);
macaw.controller(Osm_Ui_Menus.PopupMenu.SubmenuItem, PopupMenu_SubmenuItem);

macaw.controller(Osm_Ui_Menus.MenuBar.Menu, MenuBar_Menu);
macaw.controller(Osm_Ui_Menus.MenuBar.CommandItem, MenuBar_CommandItem);
macaw.controller(Osm_Ui_Menus.MenuBar.LinkItem, MenuBar_LinkItem);
macaw.controller(Osm_Ui_Menus.MenuBar.CheckboxItem, MenuBar_CheckboxItem);
macaw.controller(Osm_Ui_Menus.MenuBar.SubmenuItem, MenuBar_SubmenuItem);
