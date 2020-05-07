import BaseMenu from "../Menu";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";

export default class Menu extends BaseMenu {
    get events() {
        return Object.assign({}, super.events, {
            'button:click &___show_more': 'onShowMore',
        });
    }

    get mobile_menu() {
        let selector = '#' + this.getAliasedId('&___mobile_menu');
        return macaw.get(selector, PopupMenu);
    }

    onShowMore(e) {
        this.mobile_menu.open(e.currentTarget, {
            overlap_y: false,
            leftwards: true,
        });
    }
};
