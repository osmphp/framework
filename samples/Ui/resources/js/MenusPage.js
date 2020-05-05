import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";

export default class MenusPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'button:click #popup_test__button': 'onButtonClick',

            'menuitem:command:bold #bar': 'onBold',
            'menuitem:checked:underline #bar': 'onUnderline',

            'menuitem:command:bold #popup_test__menu': 'onBold',
            'menuitem:checked:underline #popup_test__menu': 'onUnderline',
        });
    }

    get menu() {
        return macaw.get('#popup_test__menu', PopupMenu);
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }

    onBold() {
        console.log('Bold');
    }

    onUnderline(e) {
        console.log('Underline: ' + e.detail.checked);
    }
};