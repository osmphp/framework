import ViewModel from "Osm_Framework_Js/ViewModel";
import addClass from "Osm_Framework_Js/addClass";
import removeClass from "Osm_Framework_Js/removeClass";

/**
 * @property {string} model.submitting_message
 */
export default class Fields extends ViewModel {
    onResize() {
        if (this.element.offsetWidth < 480) { // TODO: make configurable
            removeClass(this.element, '-wide');
        }
        else {
            addClass(this.element, '-wide');
        }
    }
};