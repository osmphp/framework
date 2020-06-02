import ViewModel from 'Osm_Framework_Js/ViewModel';
import addClass from 'Osm_Framework_Js/addClass';
import removeClass from 'Osm_Framework_Js/removeClass';
import getViewPortRect from 'Osm_Ui_Aba/getViewPortRect';
import cssNumber from "Osm_Framework_Js/cssNumber";

/**
 * @property {Handle} handle
 */
export default class SnackBar extends ViewModel {
    onAttach() {
        this.min_width = cssNumber(getComputedStyle(this.element).minWidth);
        super.onAttach();
    }

    onResize() {
        let rect = getViewPortRect('snack-bar');

        if (rect.width < this.min_width) {
            addClass(this.element, '-full-width');
        }
        else {
            removeClass(this.element, '-full-width');
        }
    }
};