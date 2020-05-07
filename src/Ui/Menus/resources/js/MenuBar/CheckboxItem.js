import Item from "./Item";
import macaw from "Osm_Framework_Js/vars/macaw";
import Button from "Osm_Ui_Buttons/Button";

export default class CheckboxItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click &__button': 'onButtonClick',
        });
    }

    get mobile_menu_events() {
        return Object.assign({}, super.mobile_menu_events, {
            'menuitem:checked': 'onMobileItemChecked',
        });
    }

    get button_element() {
        return document.getElementById(this.getAliasedId('&__button'));
    }

    get button() {
        return macaw.get(this.button_element, Button);
    }

    get checked() {
        return this.model.checked;
    }

    set checked(value) {
        this.model.checked = value;

        this.button.icon = value ? '-checked' : '-empty';

        if (!this.dont_notify_mobile_item) {
            this.mobile_menu_item.checked = value;
        }

        this.trigger('checked', {checked: value});
    }

    onButtonClick() {
        this.checked = !this.checked;
    }

    onMobileItemChecked(e) {
        if (e.detail.name !== this.name) {
            return;
        }

        this.withoutNotifyingMobileItem(() => {
            this.checked = e.detail.checked;
        });
    }
};
