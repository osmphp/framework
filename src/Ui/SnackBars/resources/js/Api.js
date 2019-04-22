import templates from 'Manadev_Framework_Js/vars/templates';
import $ from 'jquery';
import Mustache from 'mustache';
import Handle from './Handle';
import m_ from 'Manadev_Framework_Js/m_';
import config from 'Manadev_Framework_Js/vars/config';
import macaw from "Manadev_Framework_Js/vars/macaw";
import view_models from "Manadev_Framework_Js/vars/view_models";
import isString from "Manadev_Framework_Js/isString";
import ajax from "Manadev_Framework_Js/ajax";

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
            view_models.get($element[0]).model.handle = handle;
            macaw.afterInserted($element[0]);

            handle.element = $element[0];
        });

        return handle;
    }

    handleEmptyPayload(payload) {
        if (!isString(payload) || payload.length) {
            return false;
        }

        this.showMessage(m_("Request processing was interrupted."));
        return true;
    }

    handleServerError(xhr) {
        if (xhr instanceof Error) {
            this.show('exception', {
                message: xhr.message,
                stack_trace: xhr.stack
            });
            return false;
        }

        if (xhr.getResponseHeader("Content-Type") == 'application/json') {
            return false;
        }

        let statusText = xhr.getResponseHeader('status-text');
        if (!statusText) {
            console.log('Empty status text received: ', xhr);
            return true;
        }

        if (!xhr.responseText) {
            this.showMessage(statusText);
            return true;
        }

        this.show('exception', {
            message: statusText,
            stack_trace: xhr.responseText
        });
        return true;
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

    ajax(route, options) {
        let snackBar = this.modalMessage(options.processing_message || m_("Processing ..."));
        return ajax(route, options)
            .then(payload => {
                if (payload === undefined) return payload; // response fully handled by previous then()

                if (this.handleEmptyPayload(payload)) {
                    // subsequent then() will know than response is fully handled
                    return undefined;
                }

                return payload;
            })
            .catch(xhr => {
                if (this.handleServerError(xhr)) {
                    // subsequent then() will know than response is fully handled
                    return undefined;
                }

                // pass error response to subsequent catch
                throw xhr;
            })
            .finally(() => {
                snackBar.close();
            });
    }
};