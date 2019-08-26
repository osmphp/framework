import Field from './Field';
import macaw from 'Osm_Framework_Js/vars/macaw';

export default class FieldGroup extends Field {
    get fields() {
        let result = [];
        this.findFieldsIn(result, this.element);
        return result;
    }

    findFieldsIn(result, element) {
        for (let i = 0; i < element.children.length; i++) {
            let field = macaw.get(element.children[i], Field);
            if (field) {
                result.push(field);
                continue;
            }

            this.findFieldsIn(result, element.children[i]);
        }
    }

    validate() {
        let result = true;

        this.fields.forEach(field => {
            if (!field.validate()) {
                result = false;
            }
        });

        return result;
    }

    get value() {
        let result = null;

        this.fields.forEach(field => {
            let value = field.value;
            if (value === null) {
                return;
            }

            let name = field.name;
            if (name) {
                if (!result) {
                    result = {};
                }

                result[name] = field.value;
            }
            else {
                if (!result) {
                    result = [];
                }
                result.push(field.value);
            }
        });

        return result;
    }

    /**
     * Example path: general/related/25/price, split by '/'
     *
     * @param {string[]} path
     */
    findFieldByPath(path) {
        let index = 0;
        let result = null;
        this.fields.forEach(field => {
            let name = field.name || '' + index++;
            if (name === path[0]) {
                result = path.length == 1 ? field : field.findFieldByPath(path.slice(1));
            }
        });
        return result;
    }
};
