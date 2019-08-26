import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_PopupMenus/PopupMenu";

export default class Submenu extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click &___button': 'onClick'
        });
    }

    get alias_base() {
        return this.element.id.substr(0, this.element.id.length - '___item'.length);
    }

    onClick(e) {
        let menu = macaw.get('#' + this.getAliasedId('&'), PopupMenu);
        menu.open(e.currentTarget, {overlap_y: false});
    }
};