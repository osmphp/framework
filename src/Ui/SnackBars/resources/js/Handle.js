import Object_ from "Manadev_Framework_Js/Object_";
import $ from "jquery";

/**
 * @property {Promise} promise
 * @property {boolean} modal
 * @property {number} id
 * @property {HTMLElement} element
 * @property {boolean} closed
 * @property {Object} variables
 */
export default class Handle extends Object_ {
    close() {
        this.closed = true;

        if (!this.element) {
            return;
        }

        let $element = $(this.element);
        $element.next().remove(); // remove script tag
        $element.remove();
        this.element = null;

        if (this.modal) {
            $('.overlay').removeClass('-modal');
        }
    }
}