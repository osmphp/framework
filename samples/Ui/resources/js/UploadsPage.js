import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";

export default class UploadsPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'button:click #popup_button': 'onButtonClick',
        });
    }

    get menu() {
        return macaw.get('#popup_menu', PopupMenu);
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }
};