import merge from 'Osm_Framework_Js/merge';

import PopupMenu from './PopupMenu/Menu';
import PopupCommandItem from './PopupMenu/CommandItem';
import PopupLinkItem from './PopupMenu/LinkItem';

merge(window, {
    Osm_Ui_Menus: {
        PopupMenu: {
            Menu: PopupMenu,
            CommandItem: PopupCommandItem,
            LinkItem: PopupLinkItem,
        }
    }
});
