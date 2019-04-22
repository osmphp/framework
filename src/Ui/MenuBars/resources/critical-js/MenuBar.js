import ViewModel from "Manadev_Framework_Js/ViewModel";
import addClass from "Manadev_Framework_Js/addClass";
import removeClass from "Manadev_Framework_Js/removeClass";

export default class MenuBar extends ViewModel {
    onResize() {
        if (this.areAllItemsInOneRow()) {
            removeClass(this.element, '-show-more');
        }
        else {
            addClass(this.element, '-show-more');
        }
    }

    areAllItemsInOneRow() {
        let top;
        let result = true;

        Array.prototype.forEach.call(this.element.querySelectorAll('.menu-bar__item'), element => {
            if (top === undefined) {
                top = element.offsetTop;
                return;
            }

            if (top !== element.offsetTop) {
                result = false;
            }
        });

        return result;
    }
};