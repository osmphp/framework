import Controller from 'Osm_Framework_Js/Controller';
import addClass from 'Osm_Framework_Js/addClass';
import removeClass from 'Osm_Framework_Js/removeClass';
import hasClass from 'Osm_Framework_Js/hasClass';
import osm_t from 'Osm_Framework_Js/osm_t';

export default class Field extends Controller {
    validate() {
        if (this.value === this.error_value) {
            return false;
        }

        this.hideError();

        if (this.model.required && !this.value) {
            this.showError(osm_t("Fill in this field"));
            return false;
        }

        return true;
    }

    get name() {
        throw 'Not implemented';
    }

    get value() {
        throw 'Not implemented';
    }

    showError(message) {
        this.$error.html(message);
        addClass(this.element, '-error');
        this.error_value = this.value;
    }

    hideError() {
        removeClass(this.element, '-error');
        delete this.error_value;
    }

    get $error() {
        return this.$element.find('.field__error');
    }

    activate() {
        addClass(this.element, '-active');
    }

    deactivate() {
        if (this.deactivationPrevented) {
            return;
        }
        removeClass(this.element, '-active');

        if (hasClass(this.element, '-error')) {
            this.validate();
        }
    }
};
