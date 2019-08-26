import templates from 'Osm_Framework_Js/vars/templates';
import $ from 'jquery';
import Mustache from 'mustache';
import broadcasts from 'Osm_Framework_Js/vars/broadcasts';
import macaw from "Osm_Framework_Js/vars/macaw";
import ModalDialog from "./ModalDialog";

/**
 * @property {int} last_id
 */
export default class Api {
    constructor() {
        this.last_id = 0;
    }

    show(template, variables = {}) {
        let element;

        return templates.get('dialog__' + template).then(html => {
            variables = Object.assign({}, {
                width: 700,
                height: 500,
                id: 'dialog-' + ++this.last_id
            }, variables);

            // prevent clicks outside of modal dialog
            $('.overlay').addClass('-modal');

            // show dialog
            html = Mustache.render(html, variables);
            let $element = $(html);
            element = $element[0];
            $(document.body).append($element);
            macaw.afterInserted(element);

            // pipe keyboard events to the dialog instead of the page
            broadcasts.capture(element);

            return new Promise((resolve, reject) => {
                 let dialog = macaw.get(element, ModalDialog);
                 dialog.variables = variables;
                 dialog.resolve = resolve;
                 dialog.reject = reject;
            });
        }).finally(() => {
            $(element).remove();
            element = undefined;

            $('.overlay').removeClass('-modal');

            broadcasts.releaseCapture();
        });
    }

    yesNo(message, variables = {}) {
        return this.show('yes_no', Object.assign({
            width: 500,
            height: 150,
            message: message
        }, variables));
    }
};