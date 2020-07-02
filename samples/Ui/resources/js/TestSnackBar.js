import Controller from "Osm_Framework_Js/Controller";

export default class TestSnackBar extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click &__close': 'onCloseClick'
        });
    }

    onAttach() {
        super.onAttach();
        document.getElementById(this.element.id + '__close').focus();
    }

    onCloseClick() {
        this.element.osm_snackbar_handle.close();
    }
};