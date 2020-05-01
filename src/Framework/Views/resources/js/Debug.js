import Controller from "Osm_Framework_Js/Controller";

export default class Debug extends Controller {
    onAttach() {
        super.onAttach();

        this.element.setAttribute('debug-view',
            this.model.view);
        this.element.setAttribute('debug-template',
            this.model.template);

        requestAnimationFrame(() => {
            let controllers = [];
            this.element.osm_controllers.forEach(controller => {
                if (controller.constructor.name !== 'Debug') {
                    controllers.push(controller.constructor.name);
                }
            });

            if (controllers.length) {
                this.element.setAttribute('debug-controllers',
                    controllers.join(','));
            }
        });
    }
};
