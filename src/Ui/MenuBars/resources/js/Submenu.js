import Controller from "Manadev_Framework_Js/Controller";
import popupMenus from "Manadev_Ui_PopupMenus/vars/popupMenus";

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
        popupMenus.open(this.getAliasedId('&'), e.currentTarget, {overlap_y: false});
    }
};