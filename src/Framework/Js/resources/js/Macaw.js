import isString from "./isString";
import view_models from "./vars/view_models";
import Controller from "./Controller";
import matches from "./matches";

/**
 * MACAW = Model - Actions - Controller - Actions - vieW
 *
 * Concepts
 * --------
 *
 * Model is raw data typically passed via constructor in page source. "{"required":true}" is model in the following
 * page source snippet:
 *
 *      <script>new Manadev_Ui_Inputs.Input('#username', {"required":true});</script>
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

    /**
     * Called while page is being loaded to bind controller to every ViewModel or View matching first argument
     *
     * @param selector
     * @param Controller
     * @param model
     */
    controller(selector, Controller, model) {
        if (isString(selector)) {
            this.selector_controllers.push({
                selector: selector,
                controller_class: Controller,
                model: model
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

        if (element.m_controllers) {
            element.m_controllers.forEach(controller => {
                controller.onDetach();
                controller.view_model = null;
                controller.element = null;
            });
            delete element.m_controllers;
        }
    }

    /**
     * Called after element is inserted into DOM so that controllers can bind to new elements
     * @param element
     */
    afterInserted(element) {
        this.attachControllersToViewModels(element);
        view_models.clear();

        // find all elements matching registered CSS selectors and attach controller object to each of them
        this.selector_controllers.forEach(binding => {
            if (matches(element, binding.selector)) {
                this.attachControllerToElement(binding.controller_class, element, null, binding.model);
            }

            this.attachControllerToElements(binding.controller_class,
                element.querySelectorAll(binding.selector),
                binding.model);
        });
    }

    attachControllerToElements(controllerClass, elements, model) {
        Array.prototype.forEach.call(elements, element => {
            this.attachControllerToElement(controllerClass, element, null, model);
        });

    }

    attachControllerToElement(Controller, element, viewModel, model) {
        let controller = new Controller(element, viewModel, model);

        if (!element.m_controllers) {
            element.m_controllers = [];
        }
        element.m_controllers.push(controller);

        controller.onAttach();
    }

    attachControllersToViewModels(element) {
        if (view_models.has(element)) {
            this.attachControllersToViewModel(view_models.get(element));
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
        if (!element.m_controllers) {
            return null;
        }

        for (let i = 0; i < element.m_controllers.length; i++) {
            if (element.m_controllers[i] instanceof Controller) {
                return element.m_controllers[i];
            }
        }

        return null;
    }

    all(element) {
        return element.m_controllers || [];
    }
};