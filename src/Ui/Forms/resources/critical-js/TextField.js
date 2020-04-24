import ViewModel from 'Osm_Framework_Js/ViewModel';
import autosize from 'autosize';

export default class TextField extends ViewModel {
    onAttach() {
        super.onAttach();
        autosize(this.element.querySelector('.field__value'));
    }
};

