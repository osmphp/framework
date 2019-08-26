import Controller from 'Osm_Framework_Js/Controller';

export default class Role extends Controller {
    onAttach() {
        super.onAttach();

        this.$element.attr('role', this.role);
    }
};