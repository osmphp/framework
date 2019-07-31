import Controller from "Manadev_Framework_Js/Controller";
import Item from "./Item";

export default class MenuBar extends Controller {
    get events() {
        return Object.assign({}, super.events, {
        });
    }

    item(name) {
        let element = document.getElementById(this.getAliasedId(`&__${name}`));
        if (!element) {
            throw new Error(`Menu item '${name}' not found`);
        }

        return new Item(this, name, element);
    }
};