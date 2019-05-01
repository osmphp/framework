import macaw from "Manadev_Framework_Js/vars/macaw";
import PopupMenu from "./PopupMenu";

export default class Api {
    get(menuElementId) {
        let element = document.getElementById(menuElementId);
        if (!element) {
            throw new Error(`Menu '${menuElementId}' not found`);
        }
        return macaw.get(element, PopupMenu);
    }

    open(menuElementId, anchorElement, options = {}) {
        this.get(menuElementId).open(anchorElement, options);
    }
};