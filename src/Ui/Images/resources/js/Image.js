import Controller from 'Osm_Framework_Js/Controller';

export default class Image extends Controller {
    onAttach() {
        super.onAttach();

        let src = this.element.getAttribute('data-src');
        if (src) {
            this.element.setAttribute('src', src);
            this.element.removeAttribute('data-src');
        }
    }
};