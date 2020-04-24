import snackBars from 'Osm_Ui_SnackBars/vars/snackBars';
import FieldGroup from './FieldGroup';
import trigger from 'Osm_Framework_Js/trigger';
import url from 'Osm_Framework_Js/vars/url';
import ajax from 'Osm_Framework_Js/ajax';

export default class Form extends FieldGroup {
    get events() {
        return Object.assign({}, super.events, {
            'submit': 'onSubmit'
        });
    }

    submit() {
        this.onSubmit();
    }

    onSubmit(e) {
        // form content will be sent via AJAX, so we prevent default submit behavior which sends POST request
        // and reloads the page
        if (e) {
            e.preventDefault();
        }

        if (!this.validate()) {
            return;
        }

        let options = {
            payload: this.value,
            snackbar_message: this.model.submitting_message
        };

        trigger(this.element, 'form:submitting', options);

        ajax(this.element.method + ' ' + url.formAction(this.element.action), options)
            .then(payload => {
                if (payload === undefined) return payload; // response fully handled by previous then()

                try {
                    this.onSuccess(payload);
                    return payload;
                }
                catch (e) {
                    console.error(e);
                    throw e;
                }
            })
            .catch(xhr => {
                throw this.onError(xhr, JSON.parse(xhr.responseText))
                    ? xhr
                    : new Error('Unhandled form error');
            });
    }

    onSuccess(payload) {
        trigger(this.element, 'form:success', payload);
    }

    onError(xhr, payload) {
        if (payload.error == 'validation_failed') {
            Object.keys(payload.messages).forEach(path => {
                let parts = path.split('/');
                let field = this.findFieldByPath(parts);
                if (!field) {
                    console.log("Field '" + path + "' not found");
                    return;
                }
                field.showError(payload.messages[path]);
            });

            snackBars.showMessage(xhr.getResponseHeader('Status-Text'));
            return true;
        }

        return this.onOtherError(xhr, payload);
    }

    onOtherError(xhr, payload) {
        snackBars.showMessage(xhr.getResponseHeader('Status-Text'));
        return true;
    }
};
