import InputField from './InputField';
import intlParseFloat from "Osm_Framework_Js/intlParseFloat";

export default class PriceField extends InputField {
    get value() {
        let result = super.value;
        if (!result) {
            return result;
        }
        return intlParseFloat(result);
    }
};
