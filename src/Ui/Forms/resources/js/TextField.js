import InputField from './InputField';
import autosize from "autosize";

export default class TextField extends InputField {
    onAttach() {
        super.onAttach();
        autosize(this.element.querySelector('.field__value'));
    }
};
