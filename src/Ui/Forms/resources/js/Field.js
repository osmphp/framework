import Controller from 'Manadev_Framework_Js/Controller';

export default class Field extends Controller {
    validate() {
        return true;
    }

    get name() {
        return null;
    }

    get value() {
        return null;
    }

    showError(message) {
    }

    hideError() {
    }
};
