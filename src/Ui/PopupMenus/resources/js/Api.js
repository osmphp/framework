import macaw from "Manadev_Framework_Js/vars/macaw";
import PopupMenu from "./PopupMenu";

export default class Api {
    open(menuElementId, anchorElement, options = {}) {
        let element = document.getElementById(menuElementId);
        if (!element) {
            throw new Error(`Menu '${menuElementId}' not found`);
        }
        let controller = macaw.get(element, PopupMenu);

        controller.open(anchorElement, options);
    }
};