import Controller from "Osm_Framework_Js/Controller";
import trigger from "Osm_Framework_Js/trigger";
import addClass from "Osm_Framework_Js/addClass";
import removeClass from "Osm_Framework_Js/removeClass";

export default class Button extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClick',
        });
    }

    get icon_element() {
        return this.element.querySelector('.button__icon');
    }

    get icon() {
        let element = this.icon_element;

        if (!element) {
            return undefined;
        }

        for (let i = 0; i < element.classList.length; i++) {
            let result = element.classList.item(i);
            if (result.startsWith('-')) {
                return result;
            }
        }

        return undefined;
    }

    set icon(value) {
        let element = this.icon_element;

        if (!element) {
            return;
        }

        let currentValue = this.icon;
        if (currentValue) {
            removeClass(element, currentValue);
        }

        if (value) {
            addClass(element, value);
        }
    }

    onClick() {
        trigger(this.element, 'button:click');
    }
};