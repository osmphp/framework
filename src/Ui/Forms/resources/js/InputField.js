import Field from './Field';

export default class InputField extends Field {
    get events() {
        return Object.assign({}, super.events, {
            'focus .field__value': 'onFocus',
            'blur .field__value': 'onBlur',
            'mousedown': 'onMouseDown'
        });
    }

    get $value() {
        return this.$element.find('.field__value');
    }

    onAttach() {
        super.onAttach();

        if (this.model.focus) {
            this.$value.focus();
            this.activate();
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

    get name() {
        let result = this.$value.attr('name');
        if (!result.length) {
            return null;
        }

        if (this.model.prefix) {
            result = result.substr(this.model.prefix.length);
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
