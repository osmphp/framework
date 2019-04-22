import Controller from "Manadev_Framework_Js/Controller";
import getPopupMenu from "Manadev_Ui_PopupMenus/getPopupMenu";

export default class MenusPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click #popup_test__button': 'onButtonClick'
        });
    }

    get menu() {
        return getPopupMenu('popup_test__menu');
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }
};