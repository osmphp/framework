import templates from 'Osm_Framework_Js/vars/templates';
import $ from 'jquery';
import Mustache from 'mustache';
import Handle from './Handle';
import config from 'Osm_Framework_Js/vars/config';
import macaw from "Osm_Framework_Js/vars/macaw";
import SnackBar from "./SnackBar";

/**
 * @property {int} last_id
 */
export default class Api {
    constructor() {
        this.last_id = 0;
    }

    modalMessage(message) {
        return this.modal('message', {message});
    }

    modal(template, variables = {}) {
        return this.show(template, variables, {modal: true});
    }

    showMessage(message) {
        return this.show('message', {message});
    }

    show(template, variables = {}, options = {}) {
        options = Object.assign({
            modal: false,
            timeout: config.close_snack_bars_after * 1000
        }, options);

        let promise = templates.get('snack-bar__' + template);
        let handle = new Handle({promise, modal: options.modal, id: ++this.last_id, variables});

        promise.then(html => {
            if (handle.closed) {
                return;
            }

            handle.variables.id = 'snack-bar-' + handle.id;
            html = Mustache.render(html, handle.variables);

            let $element = $(html);
            if (options.modal) {
                $('.overlay').addClass('-modal');
            }
            else {
                setTimeout(() => {
                    handle.close();
                }, options.timeout);
            }

            $('.snack-bar-panel').append($element);
            macaw.getViewModel($element[0], SnackBar).model.handle = handle;
            macaw.afterInserted($element[0]);

            handle.element = $element[0];
        });

        return handle;
    }

    showLastingMessage(message) {
        sessionStorage.setItem('lasting_snack_bar', JSON.stringify({
            message: message,
            expiration_time: Date.now() + config.close_snack_bars_after * 1000
        }));
        return this.showMessage(message);
    }

    restoreLastingMessage() {
        let lastingSnackBar = sessionStorage.getItem('lasting_snack_bar');
        if (!lastingSnackBar) {
            return;
        }
        sessionStorage.removeItem('lasting_snack_bar');

        lastingSnackBar = JSON.parse(lastingSnackBar);
        let timeout = lastingSnackBar.expiration_time - Date.now();
        if (timeout <= 0) {
            return;
        }

        return this.showMessage(lastingSnackBar.message, {timeout});
    }
};