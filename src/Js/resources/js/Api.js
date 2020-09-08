import Object_ from './Object_';

export default class Api extends Object_ {
    constructor(controller, data) {
        super(data);

        if (!this.controller) {
            this.controller = controller;
        }
        if (!this.model) {
            this.model = controller.view;
        }
    }
};
