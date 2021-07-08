export default class Controller {
    /**
     * @type {HTMLElement}
     */
    element;

    /**
     * @type {object}
     */
    options = {};

    /**
     * @param {HTMLElement} element
     * @param {object} options
     */
    constructor(element, options) {
        this.element = element;
        this.options = options;
    }

    onAttaching() {
    }

    onAttached() {
    }
}