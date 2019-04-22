import macaw from "Manadev_Framework_Js/vars/macaw";
import PopupMenu from "Manadev_Ui_PopupMenus/PopupMenu";

export default function getPopupMenu(elementId) {
    let element = document.getElementById(elementId);
    if (!element) {
        throw new Error(`Menu '${elementId}' not found`);
    }
    return macaw.get(element, PopupMenu);
}