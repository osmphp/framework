import BaseMenu from '../Menu';

export default class Menu extends BaseMenu {
    get item_selector() {
        return '.menu-bar__item';
    }

    get show_more_element() {
        return this.element.querySelector('.menu-bar__show-more')
    }

    get mobile_menu_element() {
        return document.getElementById(this.getAliasedId('&___mobile_menu'));
    }

    get mobile_menu_item_elements() {
        return this.mobile_menu_element.querySelectorAll('.popup-menu__item');
    }

    onResize() {
        let itemElements = this.item_elements;
        let firstRowItemCount = this.countFirstRowItems(itemElements);

        // if only one item fits into the first row and there is more, then we
        // hide it but let it still occupy the space
        if (itemElements.length > 1 && firstRowItemCount === 1) {
            itemElements[0].classList.add('-hidden-but-consuming-space');
            firstRowItemCount = 0;
        }
        else {
            itemElements[0].classList.remove('-hidden-but-consuming-space');
        }

        // if there are items that didn't fit into the first row, show the
        // "show more" hamburger
        if (firstRowItemCount < itemElements.length) {
            this.show_more_element.classList.remove('-hidden');
        }
        else {
            this.show_more_element.classList.add('-hidden');
        }

        // in the mobile menu, hide all the items which are visible
        // in the first row of this menu bar
        this.hideFirstMobileItems(firstRowItemCount);
    }

    countFirstRowItems(itemElements) {
        let top;
        let result = 0;

        Array.prototype.forEach.call(itemElements, itemElement => {
            if (itemElement.classList.contains('-hidden')) {
                return;
            }

            if (top === undefined) {
                top = itemElement.offsetTop;
            }

            if (top === itemElement.offsetTop) {
                result++;
            }
        });

        return result;

    }

    hideFirstMobileItems(count) {
        let itemElements = this.mobile_menu_item_elements;
        Array.prototype.forEach.call(itemElements, (element, index) => {
            if (index < count) {
                element.classList.add('-invisible');
            }
            else {
                element.classList.remove('-invisible');
            }
        });

        this.rearrangeDelimiters(itemElements);
    }
};

