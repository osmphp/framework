import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import Item from "./Item";

export default class Menu extends Controller {
    get item_selector() {
        throw 'Not implemented';
    }

    get item_elements() {
        return this.element.querySelectorAll(this.item_selector);
    }

    rearrangeDelimiters(itemElements = null) {
        let delimiterHidden = false;
        if (!itemElements) {
            itemElements = this.item_elements;
        }

        Array.prototype.forEach.call(itemElements, itemElement => {
            // if delimiter item is hidden, set a boolean for showing
            // the first visible item after it as a delimiter item
            if (itemElement.classList.contains('-delimiter')) {
                delimiterHidden = itemElement.classList.contains('-hidden');
                return;
            }

            // don't set hidden items as delimiter items
            if(delimiterHidden && !itemElement.classList.contains('-hidden')) {
                itemElement.classList.add('-delimiter-copy');
            }
            else {
                itemElement.classList.remove('-delimiter-copy');
            }
        });
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

    onAttach() {
        super.onAttach();
        this.rearrangeDelimiters();
    }
};
