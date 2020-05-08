import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import Item from "./Item";

export default class Menu extends Controller {
    get item_selector() {
        this.view_model.item_selector;
    }

    rearrangeDelimiters() {
        this.view_model.rearrangeDelimiters();
    }

    getItemElement(name) {
        return document.getElementById(this.getAliasedId(`&__${name}`));
    }

    getItem(name) {
        let pos = name.indexOf('.');
        if (pos === -1) {
            return macaw.get(this.getItemElement(name), Item);
        }

        let submenuItem = macaw.get(this.getItemElement(name.substr(0, pos)),
            Item);
        if (!submenuItem) {
            return null;
        }

        return submenuItem.submenu.getItem(name.substr(pos + 1));
    }
};
