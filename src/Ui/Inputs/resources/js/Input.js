import Field from 'Osm_Ui_Forms/Field';
import addClass from 'Osm_Framework_Js/addClass';
import removeClass from 'Osm_Framework_Js/removeClass';
import hasClass from 'Osm_Framework_Js/hasClass';
import osm_t from 'Osm_Framework_Js/osm_t';

export default class Input extends Field {
    get events() {
        return Object.assign({}, super.events, {
            'focus .input__value': 'onFocus',
            'blur .input__value': 'onBlur',
            'mousedown': 'onMouseDown'
        });
    }

    get $value() {
        return this.$element.find('.input__value');
    }
    get $error() {
        return this.$element.find('.input__error');
    }

    onAttach() {
        super.onAttach();

        // wait until animation frame as browser may auto fill form inputs and we need to update label position
        // after that
        requestAnimationFrame(() => {
            this.update();
        });

        if (this.model.focus) {
            this.$value.focus();
        }
    }

    focus() {
        this.$value.focus();
    }

    update() {
        if (this.$value.is(':focus')) {
            this.activate();
        }
        else {
            this.deactivate();
        }
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
        removeClass(this.element, '-large-title');
    }

    deactivate() {
        if (this.deactivationPrevented) {
            return;
        }
        removeClass(this.element, '-active');

        if (!this.value && !this.isAutoFilled()) {
            addClass(this.element, '-large-title');
        }
        else {
            removeClass(this.element, '-large-title');
        }

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
        return result.length ? result : null;
    }

    get value() {
        return this.$value.val().trim();
    }

    set value(value) {
        this.$value.val(value);
        this.update();
    }

    isAutoFilled() {
        try {
            // :-webkit-autofill pseudo class tells if input is auto filled in Chrome
            return this.$value.is(':-webkit-autofill');
        }
        catch (e) {
            // on other browsers check for :-webkit-autofill pseudo class may cause an error
            return false;
        }
    }
};
