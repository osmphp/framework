import Item from "../Item";
import macaw from "Osm_Framework_Js/vars/macaw";
import Button from "Osm_Ui_Buttons/Button";

export default class CheckboxItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click &__button': 'onButtonClick',
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

        this.button.icon = value ? '-checked' : '-unchecked';

        this.trigger('checked', {checked: value});
    }

    onButtonClick() {
        this.checked = !this.checked;
    }
};
