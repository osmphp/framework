import Controller from "Osm_Framework_Js/Controller";

export default class WidthClass extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'resize window': 'onResize',
        });
    }

    onAttach() {
        super.onAttach();

        this.rules = {
            'min-width-': 'minWidth',
        };

        this.onResize();
    }

    onResize() {
        Object.getOwnPropertyNames(this.model).forEach(property => {
            let match = property.match(/^([a-z\-]+)considered([a-z\-]+)/);

            if (!match) {
                return;
            }

            this[this.rules[match[1]]](match[2], this.model[property]);
        });
    }

    minWidth(class_, width) {
        if (this.element.offsetWidth < width) {
            this.element.classList.remove(class_);
        }
        else {
            this.element.classList.add(class_);
        }
    }
};
