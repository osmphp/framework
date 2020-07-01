import InputField from './InputField';

export default class PriceField extends InputField {
    get value() {
        return parseFloat(super.value);
    }
};
