import ViewModel from 'Osm_Framework_Js/ViewModel';

export default class Menu extends ViewModel {
    onAttach() {
        super.onAttach();
        this.rearrangeDelimiters();
    }

    get item_selector() {
        throw 'Not implemented';
    }

    get item_elements() {
        return this.element.querySelectorAll(this.item_selector);
    }

    rearrangeDelimiters(itemElements = null) {
        let delimiterHidden = false;
        if (!itemElements) {
            itemElements = this.item_elements;
        }

        Array.prototype.forEach.call(itemElements, itemElement => {
            // if delimiter item is hidden, set a boolean for showing
            // the first visible item after it as a delimiter item
            if (itemElement.classList.contains('-delimiter')) {
                delimiterHidden = itemElement.classList.contains('-hidden');
                return;
            }

            // don't set hidden items as delimiter items
            if(delimiterHidden && !itemElement.classList.contains('-hidden')) {
                itemElement.classList.add('-delimiter-copy');
            }
            else {
                itemElement.classList.remove('-delimiter-copy');
            }
        });
    }
};

