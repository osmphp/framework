import Item from "../Item";
import addClass from "Osm_Framework_Js/addClass";
import removeClass from "Osm_Framework_Js/removeClass";

export default class CheckboxItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClick',
        });
    }

    get icon_element() {
        return this.element.querySelector('.popup-menu__icon .icon');
    }

    get checked() {
        return this.model.checked;
    }

    set checked(value) {
        this.model.checked = value;

        if (value) {
            addClass(this.icon_element, '-checked');
        }
        else {
            removeClass(this.icon_element, '-checked');
        }

        this.trigger('checked', {checked: value});
    }

    onClick() {
        this.checked = !this.checked;
        this.menu.close();
    }
};
