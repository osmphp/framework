import ViewModel from 'Manadev_Framework_Js/ViewModel';
import addClass from 'Manadev_Framework_Js/addClass';
import removeClass from 'Manadev_Framework_Js/removeClass';
import getViewPortRect from 'Manadev_Ui_Aba/getViewPortRect';

/**
 * @property {Handle} handle
 */
export default class SnackBar extends ViewModel {
    onAttach() {
        this.min_width = parseFloat(getComputedStyle(this.element).minWidth);
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