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

    get checked_css_class() {
        return '-filled';
    }

    get checked() {
        return this.model.checked;
    }

    set checked(value) {
        this.model.checked = value;

        if (value) {
            addClass(this.button_element, this.checked_css_class);
        }
        else {
            removeClass(this.button_element, this.checked_css_class);
        }

        this.trigger('checked', {checked: value});
    }

    onButtonClick() {
        this.checked = !this.checked;
    }
};
