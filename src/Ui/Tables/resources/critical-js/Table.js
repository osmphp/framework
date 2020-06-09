import ViewModel from 'Osm_Framework_Js/ViewModel';
import cssNumber from "Osm_Framework_Js/cssNumber";

export default class Table extends ViewModel {
    onAttach() {
        this.initializeColumnWidths();
        super.onAttach();
    }

    onResize() {
        if (!this.model.main_column) {
            return;
        }

        let totalWidth = this.getTotalWidth();
        let elementStyles = getComputedStyle(this.element);
        let availableWidth = cssNumber(elementStyles.width) - cssNumber(elementStyles.paddingLeft) -
            cssNumber(elementStyles.paddingRight);
        let columnHeader = this.element.querySelector(`.table__column-header.-col-${this.model.main_column}`);
        let minimumColumnWidth = this.model.columns[this.model.main_column].width;

        columnHeader.style.minWidth = totalWidth >= availableWidth
            ? `${minimumColumnWidth}px`
            : `${minimumColumnWidth + (availableWidth - totalWidth)}px`;
    }

    initializeColumnWidths() {
        for (let column in this.model.columns) {
            if (!this.model.columns.hasOwnProperty(column)) continue;

            let columnHeader = this.element.querySelector(`.table__column-header.-col-${column}`);
            columnHeader.style.minWidth = `${this.model.columns[column].width}px`;
        }
    }

    getTotalWidth() {
        let result = 0.0;

        for (let column in this.model.columns) {
            if (!this.model.columns.hasOwnProperty(column)) continue;

            result += this.model.columns[column].width;
        }

        return result;
    }
}