import isString from "./isString";
import view_models from "./vars/view_models";
import Controller from "./Controller";
import matches from "./matches";
import find from "./find";

/**
 * MACAW = Model - Actions - Controller - Actions - vieW
 *
 * Concepts
 * --------
 *
 * Model is raw data typically passed via constructor in page source. "{"required":true}" is model in the following
 * page source snippet:
 *
 *      <script>new Osm_Ui_Inputs.Input('#username', {"required":true});</script>
 *
 * View is HTML element with all its child HTML elements.
 *
 * Controller is object which listens to events (e.g. clicks) and executes actions to respond to events.
 *
 * Action is object which reads and alters state of model and view. In simple cases may be omitted (be part of
 * controller object).
 *
 * ViewModel is object create in page source which binds view to model and also is responsible for initial view
 * state and resizing.
 *
 * Responsibilities
 * ----------------
 *
 * Model:
 *      - holds server-side settings and state
 *      - even if there are no server-side settings empty model object is created
 *      - plain JS object
 * View:
 *      - is what user sees
 *      - triggers HTML events
 *      - HTMLElement
 * Controller:
 *      - handles view events by calling action methods
 *      - handles internal events by calling action methods
 *      - bound to View on DOM ready
 * ViewModel:
 *      - instantiated in script tags
 *      - initializes view
 *      - handles window resize
 *      - special part of Controller bound to View very early, just after View is parsed from page source
 * Action:
 *      - methods for retrieving model and view state
 *      - method for altering model and view state
 *      - action may call methods of other action by only for retrieval
 *
 * Flow
 * ----
 *
 * Initialization:
 *      - browser parses View HTML and creates View - HTMLElement
 *      - browser parses following script tags and creates ViewModel object, which
 *          - contains initial model
 *          - binds to View
 *          - initializes/resizes View
 *      - in the end of page load browser load non-critical JS file which binds Controller to ViewModel (or directly
 *        to View) which
 *          - starts listening to HTML events
 * HTML or internal event:
 *      - Controller does handling and calls action methods
 *      - Action methods read/write model/view state
 *      - Action methods trigger Controller internal events
 *
 */
export default class Macaw {
    constructor() {
        this.selector_controllers = [];
        this.view_model_controllers = new Map();
    }

    get debug() {
        if (this._debug === undefined) {
            this._debug = document.documentElement
                .hasAttribute('debug-view');
        }

        return this._debug;
    }

    /**
     * Called while page is being loaded to bind controller to every ViewModel or View matching first argument
     *
     * @param selector
     * @param Controller
     * @param model
     * @param attribute
     */
    controller(selector, Controller, model = null, attribute = null) {
        if (isString(selector)) {
            if (!attribute) {
                let match = selector.match(/^\.([a-z\-_]+)/);
                if (match) {
                    attribute = `data-${match[1]}`;
                }
            }

            this.selector_controllers.push({
                selector: selector,
                controller_class: Controller,
                model: model,
                attribute: attribute,
            });
        }
        else {
            this.view_model_controllers.set(selector, Controller);
        }
    }

    /**
     * Called after page is loaded and all controllers are registered
     */
    ready() {
        this.afterInserted(document.body);
    }

    /**
     * Called before removing elements from DOM so that all bound controllers can gracefully detach
     *
     * @param element
     */
    beforeRemoving(element) {
        Array.prototype.forEach.call(element.children, element => {
            this.beforeRemoving(element);
        });

        if (element.osm_controllers) {
            element.osm_controllers.forEach(controller => {
                controller.onDetach();
                controller.view_model = null;
                controller.element = null;
            });
            delete element.osm_controllers;
        }
    }

    /**
     * Called after element is inserted into DOM so that controllers can bind to new elements
     * @param element
     */
    afterInserted(element) {
        this.attachControllersToViewModels(element);
        view_models.clear();

        let allBindings = new Map();

        this.selector_controllers.forEach(binding => {
            if (matches(element, binding.selector)) {
                if (!allBindings.has(element)) {
                    allBindings.set(element, []);
                }
                allBindings.get(element).push(binding);
            }

            let elements = element.querySelectorAll(binding.selector);
            Array.prototype.forEach.call(elements, element => {
                if (!allBindings.has(element)) {
                    allBindings.set(element, []);
                }
                allBindings.get(element).push(binding);
            });
        });

        this.bindElement(element, allBindings);
    }

    bindElement(element, allBindings) {
        let bindings = allBindings.get(element) || [];

        let controllers = bindings.map(binding => {
            let controller = this.createController(binding.controller_class,
                element, null, binding.model, binding.attribute);
            controller.onAttaching();

            if (this.debug) {
                let controllers = element.hasAttribute('debug-controllers')
                    ? element.hasAttribute('debug-controllers').split(',')
                    : [];

                if (controllers.indexOf(controller.constructor.name) == -1) {
                    controllers.push(controller.constructor.name);
                    element.setAttribute('debug-controllers',
                        controllers.join(','));
                }
            }

            return controller;
        });

        Array.prototype.forEach.call(element.children, element => {
            this.bindElement(element, allBindings);
        });

        controllers.forEach(controller => {
            controller.onAttach();
        });
    }

    createController(Controller, element, viewModel, model, attribute) {
        if (attribute && element.hasAttribute(attribute)) {
            let attributeModel = JSON.parse(element.getAttribute(attribute));
            if (attributeModel) {
                model = model
                    ? Object.assign({}, model, attributeModel)
                    : attributeModel;
            }
        }

        let controller = new Controller(element, viewModel, model);

        if (!element.osm_controllers) {
            element.osm_controllers = [];
        }
        element.osm_controllers.push(controller);

        return controller;
    }

    attachControllerToElement(Controller, element, viewModel, model, attribute) {
        let controller = this.createController(Controller, element, viewModel,
            model, attribute);

        controller.onAttaching();
        controller.onAttach();
    }

    attachControllersToViewModels(element) {
        if (view_models.has(element)) {
            view_models.get(element).forEach(viewModel => {
                this.attachControllersToViewModel(viewModel);
            });
        }

        Array.prototype.forEach.call(element.children, element => {
            this.attachControllersToViewModels(element);
        });
    }

    attachControllersToViewModel(viewModel) {
        for (let class_ = viewModel.constructor; class_.prototype; class_ = Object.getPrototypeOf(class_)) {
            if (this.view_model_controllers.has(class_)) {
                this.attachControllerToElement(this.view_model_controllers.get(class_), viewModel.element, viewModel);
                return;
            }
        }

        // in case there is no controller registered for view model, we attach empty controller to make detaching
        // work
        this.attachControllerToElement(Controller, viewModel.element, viewModel);
    }

    get(element, Controller) {
        element = find(element);
        if (!element.osm_controllers) {
            return null;
        }

        for (let i = 0; i < element.osm_controllers.length; i++) {
            if (element.osm_controllers[i] instanceof Controller) {
                return element.osm_controllers[i];
            }
        }

        return null;
    }

    all(element) {
        return element.osm_controllers || [];
    }

    getViewModel(element, ViewModel) {
        element = find(element);

        let viewModels = view_models.get(element);
        if (!viewModels) {
            return null;
        }

        for (let i = 0; i < viewModels.length; i++) {
            if (viewModels[i] instanceof ViewModel) {
                return viewModels[i];
            }
        }

        return null;
    }
};