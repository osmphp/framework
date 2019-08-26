import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_PopupMenus/PopupMenu";

export default class MenusPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click #popup_test__button': 'onButtonClick'
        });
    }

    get menu() {
        return macaw.get('#popup_test__menu', PopupMenu);
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }
};