export default class App {
    controller_classes = {};

    /**
     * @param {string} controllerName
     * @param {function} controllerClass
     * @returns {*}
     */
    register(controllerName, controllerClass) {
        this.controller_classes[controllerName] = controllerClass;

        return controllerClass;
    }

    /**
     * @param {HTMLElement} element
     */
    bind(element) {
        for (let attribute of element.attributes) {
            if (!attribute.name.startsWith('data-js-')) {
                continue;
            }

            const controllerName = attribute.name.substr('data-js-'.length);
            if (!this.controller_classes[controllerName]) {
                console.warn(`Undefined controller class '${controllerName}'`);
                continue;
            }

            if (!element.osm_controllers) {
                element.osm_controllers = {};
            }

            let options = attribute.value ? JSON.parse(attribute.value) : {};

            let controllerClass = this.controller_classes[controllerName];

            let controller = new controllerClass(element, options);
            element.osm_controllers[controllerName] = controller;

            element.classList.add(`js-${controllerName}`);
            controller.onAttaching();
        }

        for (let childElement of element.children) {
            this.bind(childElement);
        }

        if (element.osm_controllers) {
            for (let controllerName in element.osm_controllers) {
                if (!element.osm_controllers.hasOwnProperty(controllerName)) {
                    continue;
                }

                let controller = element.osm_controllers[controllerName];

                controller.onAttached();
            }
        }
    }

    unbind(element) {
    }

    /**
     * @param {HTMLElement|string} element
     * @param {?string} controllerName
     * @returns {?Controller}
     */
    controller(element, controllerName) {
        if (typeof element === 'string') {
            const pos = element.indexOf(':');

            if (pos !== -1) {
                controllerName = element.substr(pos + 1);
                element = element.substr(0, pos);
            }

            element = document.querySelector(element);
        }

        if (!element) {
            return undefined;
        }

        if (!element.osm_controllers) {
            return undefined;
        }

        return element.osm_controllers[controllerName];
    }
}