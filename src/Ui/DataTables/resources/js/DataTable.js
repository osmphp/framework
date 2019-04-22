import Controller from 'Manadev_Framework_Js/Controller';
import getViewPortRect from "Manadev_Ui_Aba/getViewPortRect";
import hasClass from 'Manadev_Framework_Js/hasClass';
import removeClass from 'Manadev_Framework_Js/removeClass';
import ajax from 'Manadev_Framework_Js/ajax';
import $ from 'jquery';

export default class DataTable extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'debounce:scroll window': 'onScroll'
        });
    }

    get $table() {
        return this.$element.find('.data-table__table');
    }

    get $rows() {
        if (!this._$rows) {
            this._$rows = this.$element.find('.data-table__row');
        }
        return this._$rows;
    }

    get average_row_height() {
        let bottom = this.$rows[this.$rows.length - 1].getBoundingClientRect().bottom;
        let top = this.$rows[0].getBoundingClientRect().top;
        return (bottom - top) / this.$rows.length;
    }

    onAttach() {
        super.onAttach();
        this.renderRowPlaceholders();
        this.onScroll();

    }

    renderRowPlaceholders() {
        let rowsToBeRendered = this.model.count - this.$element.find('.data-table__row').length;
        let html = this.$element.find('.data-table__row-template').html();

        for (let i = 0; i < rowsToBeRendered; i++) {
            this.$table.append(html);
        }
    }

    onScroll() {
        let viewPortRect = getViewPortRect();

        // if there are no rows, there is nothing to be loaded
        if (!this.$rows.length) {
            return;
        }

        // find approximate visible row
        let row = Math.round((viewPortRect.top - this.$rows[0].getBoundingClientRect().top) /
            this.average_row_height);

        if (row <= 0) {
            row = 0;
        }

        if (row >= this.$rows.length - 1) {
            row = this.$rows.length - 1;
        }

        // if there are rows above it, iterate to actual first visible row
        while (row > 0 && this.$rows[row].getBoundingClientRect().top > viewPortRect.top) {
            row--;
        }

        // row range to be loaded
        let query = null;

        for(; row < this.$rows.length; row++) {
            let rect = this.$rows[row].getBoundingClientRect();

            if (rect.bottom < viewPortRect.top) {
                // don't start iterating if current row is above viewport
                continue;
            }

            if (!query) { // we are not collecting row range to be loaded yet
                if (rect.top > viewPortRect.bottom) {
                    // stop iterating if current row is below viewport
                    break;
                }

                if (hasClass(this.$rows[row], '-placeholder')) {
                    query = {_offset: row, _limit: 1};
                }
            }
            else { // we are in process of collecting row range to be loaded from server
                if (!hasClass(this.$rows[row], '-placeholder')) {
                    this.load(query);
                    query = null;
                    continue;
                }

                query._limit++;
                if (query._limit >= this.model.rows_per_page) {
                    this.load(query);
                    query = null;
                }
            }
        }

        if (query) {
            this.load(query);
        }
    }

    load(query) {
        for (let row = query._offset; row < query._offset + query._limit; row++) {
            removeClass(this.$rows[row], '-placeholder');
        }

        ajax(this.model.load_route, {query})
            .then(html => {
                let parentElement = this.$rows[query._offset].parentNode;
                let $previousElement = $(this.$rows[query._offset]).prev();

                for (let row = query._offset; row < query._offset + query._limit; row++) {
                    parentElement.removeChild(this.$rows[row]);
                }

                $previousElement.after(html);

                // delete cached array of all row elements from memory. It will be refetched next time
                // it is needed
                delete this._$rows;
            });
    }
}