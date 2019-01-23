export default class Action {
    constructor(controller) {
        this.controller = controller;
    }

    get element() { return this.controller.element; }
    get $element() { return this.controller.$element; }
    get model() { return this.controller.model; }
};