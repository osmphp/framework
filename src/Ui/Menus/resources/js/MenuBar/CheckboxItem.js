import Item from "../Item";
import addClass from "Osm_Framework_Js/addClass";
import removeClass from "Osm_Framework_Js/removeClass";

export default class CheckboxItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click &__button': 'onButtonClick',
        });
    }

    get button_element() {
        return document.getElementById(this.getAliasedId('&__button'));
    }

    get checked() {
        return this.model.checked;
    }

    set checked(value) {
        this.model.checked = value;

        if (value) {
            if (this.model.checked_button_style) {
                addClass(this.button_element, this.model.checked_button_style);
            }
            if (this.model.unchecked_button_style) {
                removeClass(this.button_element, this.model.unchecked_button_style);
            }
        }
        else {
            if (this.model.checked_button_style) {
                removeClass(this.button_element, this.model.checked_button_style);
            }
            if (this.model.unchecked_button_style) {
                addClass(this.button_element, this.model.unchecked_button_style);
            }
        }

        this.trigger('checked', {checked: value});
    }

    onButtonClick() {
        this.checked = !this.checked;
    }
};
