import Controller from 'Osm_Framework_Js/Controller';

export default class Field extends Controller {
    validate() {
        return true;
    }

    get name() {
        return null;
    }

    get value() {
        return undefined;
    }

    showError(message) {
    }

    hideError() {
    }
};
