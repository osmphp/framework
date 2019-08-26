import addClass from 'Osm_Framework_Js/addClass';
import getViewPortRect from 'Osm_Ui_Aba/getViewPortRect';
import ViewModel from "Osm_Framework_Js/ViewModel";

export default class PageDialog extends ViewModel {
    onAttach() {
        addClass(document.documentElement, '-page-dialog');
        addClass(document.body, '-page-dialog');

        super.onAttach();

        let firstFocusableElement = document.querySelector('input');
        if (firstFocusableElement) {
            firstFocusableElement.focus();
        }
    }

    onResize() {
        if (!this.model.width) {
            throw 'Required PageDialog.model.width property not set';
        }

        let rect = getViewPortRect('page-dialog');
        this.element.style.width = (this.model.width <= rect.width ? this.model.width : rect.width) + 'px';
    }
};