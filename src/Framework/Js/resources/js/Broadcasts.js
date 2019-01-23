import Controller from "./Controller";
import macaw from "./vars/macaw";

export default class Broadcasts extends Controller {
    constructor(element, viewModel, model) {
        super(element, viewModel, model);

        this.handlers = [];
    }

    get events() {
        return Object.assign({}, super.events, {
            'keydown window': 'onKeydown'
        });
    }

    get handler() {
        return this.handlers.length ? this.handlers[this.handlers.length - 1] : this.element;
    }

    capture(element) {
        this.handlers.push(element);
    }

    releaseCapture() {
        this.handlers.pop();
    }

    onKeydown(e) {
        this.catchStopPropagation(e, () => {
            this.handle(this.handler, e, 'keydown');
            this.broadcast(this.handler, e, 'keydown');
        });
    }

    handle(element, e, eventName) {
        if (e.m_propagation_stopped) {
            return;
        }

        let controllers = macaw.all(element);
        if (!controllers) {
            return;
        }

        controllers.forEach(controller => {
            if (e.m_propagation_stopped) {
                return;
            }

            if (controller === this) {
                return;
            }

            let handler = controller.events[eventName + ' broadcast'];
            if (!handler) {
                return;
            }

            controller[handler](e);
        });
    }

    broadcast(element, e, eventName) {
        if (e.m_propagation_stopped) {
            return;
        }

        for (let i = 0; i < element.children.length; i++) {
            this.handle(element.children[i], e, eventName);
        }

        for (let i = 0; i < element.children.length; i++) {
            this.broadcast(element.children[i], e, eventName);
        }
    }

    catchStopPropagation(e, callback) {
        let originalStopPropagation = e.stopPropagation;
        e.stopPropagation = function() {
            e.m_propagation_stopped = true;
            originalStopPropagation.call(e);
        };

        try {
            callback();
        }
        finally {
            e.stopPropagation = originalStopPropagation;
        }
    }
};