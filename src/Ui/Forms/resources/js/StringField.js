import Field from 'Osm_Ui_Forms/Field';
import addClass from 'Osm_Framework_Js/addClass';
import removeClass from 'Osm_Framework_Js/removeClass';
import hasClass from 'Osm_Framework_Js/hasClass';
import osm_t from 'Osm_Framework_Js/osm_t';

export default class StringField extends Field {
    get events() {
        return Object.assign({}, super.events, {
            'focus .string-field__value': 'onFocus',
            'blur .string-field__value': 'onBlur',
            'mousedown': 'onMouseDown'
        });
    }

    get $value() {
        return this.$element.find('.string-field__value');
    }
    get $error() {
        return this.$element.find('.string-field__error');
    }

    onAttach() {
        super.onAttach();

        if (this.model.focus) {
            this.$value.focus();
            this.activate();
        }
    }

    focus() {
        this.$value.focus();
    }

    onFocus() {
        this.activate();
    }

    onBlur() {
        this.deactivate();
    }

    onMouseDown(e) {
        if (this.$value.is(':focus') && e.target !== this.$value[0]) {
            // prevent value losing focus when clicked on static area of the input
            e.preventDefault();
        }
        else {
            this.activate();
            this.deactivationPrevented = true;
            requestAnimationFrame(() => {
                this.$value.focus();
                this.deactivationPrevented = false;
            });
        }
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

    showError(message) {
        this.$error.html(message);
        addClass(this.element, '-error');
        this.error_value = this.value;
    }

    hideError() {
        removeClass(this.element, '-error');
        delete this.error_value;
    }

    get name() {
        let result = this.$value.attr('name');
        if (!result.length) {
            return null;
        }

        if (this.model.autocomplete_prefix) {
            result = result.substr(this.model.autocomplete_prefix.length);
        }

        return result;
    }

    get value() {
        let result = this.$value.val().trim();
        return result.length ? result : null;
    }

    set value(value) {
        this.$value.val(value);
    }
};
