import Controller from 'Osm_Framework_Js/Controller';

export default class Hidden extends Controller {
    onAttach() {
        super.onAttach();

        this.$element.attr('aria-hidden', 'true');
    }
};