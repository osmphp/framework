import Controller from "Osm_Framework_Js/Controller";
import trigger from "Osm_Framework_Js/trigger";

export default class Button extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClick',
        });
    }

    onClick() {
        trigger(this.element, 'button:click');
    }
};