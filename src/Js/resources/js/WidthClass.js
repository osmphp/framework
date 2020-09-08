import Controller from "Osm_Framework_Js/Controller";

export default class WidthClass extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'resize window': 'onResize',
        });
    }

    onResize() {
        Object.getOwnPropertyNames(this.model).forEach(modifier => {
            this.minWidth(modifier, this.model[modifier]);
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
