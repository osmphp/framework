import merge from 'Osm_Framework_Js/merge';

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

merge(window, {
    Osm_Ui_Menus: {
        PopupMenu: {
            Menu: PopupMenu_Menu,
            CommandItem: PopupMenu_CommandItem,
            LinkItem: PopupMenu_LinkItem,
            CheckboxItem: PopupMenu_CheckboxItem,
            SubmenuItem: PopupMenu_SubmenuItem,
        },
        MenuBar: {
            Menu: MenuBar_Menu,
            CommandItem: MenuBar_CommandItem,
            LinkItem: MenuBar_LinkItem,
            CheckboxItem: MenuBar_CheckboxItem,
            SubmenuItem: MenuBar_SubmenuItem,
        },
    }
});
