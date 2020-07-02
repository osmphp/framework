import Controller from "Osm_Framework_Js/Controller";
import getViewPortRect from "Osm_Ui_Aba/getViewPortRect";
import addClass from "Osm_Framework_Js/addClass";
import removeClass from "Osm_Framework_Js/removeClass";
import cssNumber from "Osm_Framework_Js/cssNumber";

/**
 * @param {Function} resolve
 * @param {Function} reject
 * @param {Object} variables
 */
export default class ModalDialog extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'keydown broadcast': 'onKeydown',
            'resize window': 'onResize'
        });
    }

    get header() {
        if (this._header === undefined) {
            this._header = this.element.querySelector('.modal-dialog__header');
        }
        return this._header;
    }
    get body() {
        if (this._body === undefined) {
            this._body = this.element.querySelector('.modal-dialog__body');
        }
        return this._body;
    }
    get footer() {
        if (this._footer === undefined) {
            this._footer = this.element.querySelector('.modal-dialog__footer');
        }
        return this._footer;
    }

    onAttach() {
        let style = getComputedStyle(this.element);
        this.width = cssNumber(style.width);
        this.height = cssNumber(style.height);

        if (this.header) {
            let style = getComputedStyle(this.header);
            this.body.style.top = (this.header.offsetHeight +
                cssNumber(style.marginTop) + cssNumber(style.marginBottom))+ 'px';
        }
        else {
            this.body.style.top = 0;
        }

        if (this.footer) {
            let style = getComputedStyle(this.footer);
            this.body.style.bottom = (this.footer.offsetHeight +
                cssNumber(style.marginTop) + cssNumber(style.marginBottom)) + 'px';
        }
        else {
            this.body.style.bottom = 0;
        }

        super.onAttach();
    }

    onResize() {
        let rect = getViewPortRect('modal-dialog');

        this.element.style.left = (rect.width >= this.width ? (rect.width - this.width) / 2 : rect.left) + 'px';
        this.element.style.right = (rect.width >= this.width ? (rect.width - this.width) / 2 : rect.left) + 'px';
        this.element.style.top = (rect.height >= this.height ? (rect.height - this.height) / 2 : rect.top) + 'px';
        this.element.style.bottom = (rect.height >= this.height ? (rect.height - this.height) / 2 : rect.top) + 'px';
        this.element.style.width = 'auto';
        this.element.style.height = 'auto';

        if (this.body.scrollHeight > this.body.offsetHeight) {
            addClass(this.element, '-scrollable');
        }
        else {
            removeClass(this.element, '-scrollable');
        }
    }

    onKeydown(e) {
        switch (e.key) {
            case 'Escape': this.resolve(); e.stopPropagation(); break;
        }
    }
};