import Controller from "Manadev_Framework_Js/Controller";
import getPopupMenu from "Manadev_Ui_PopupMenus/getPopupMenu";

export default class SampleViewUsingPopupMenu extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click &__button': 'onButtonClick'
        });
    }

    get menu() {
        return getPopupMenu(this.getAliasedId('&__popup_menu'));
    }

    onAttach() {
        super.onAttach();
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }
};