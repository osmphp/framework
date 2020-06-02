import BaseMenu from '../Menu';
import cssNumber from "Osm_Framework_Js/cssNumber";

export default class Menu extends BaseMenu {
    get item_selector() {
        return '.menu-bar__item';
    }

    get items_element() {
        return this.element.querySelector('.menu-bar__items');
    }

    get show_more_element() {
        return this.element.querySelector('.menu-bar__show-more');
    }

    get mobile_menu_element() {
        return document.getElementById(this.getAliasedId('&___mobile_menu'));
    }

    get mobile_menu_item_elements() {
        return this.mobile_menu_element.querySelectorAll('.popup-menu__item');
    }

    onResize() {
        let itemElements = this.item_elements;
        let showMore = false;
        let containerRect = this.items_element.getBoundingClientRect();
        let containerStyle = getComputedStyle(this.element);
        let containerRight = containerRect.right -
            cssNumber(containerStyle.borderRight) -
            cssNumber(containerStyle.paddingRight);
        let count = 0;

        Array.prototype.forEach.call(itemElements, itemElement => {
            if (itemElement.classList.contains('_hidden')) {
                return;
            }

            let itemRect = itemElement.getBoundingClientRect();

            if (itemRect.right <= containerRight) {
                itemElement.classList.remove('_invisible');
                count++;
            }
            else {
                itemElement.classList.add('_invisible');
                showMore = true;
            }
        });

        if (showMore) {
            this.show_more_element.classList.remove('_hidden');
        }
        else {
            this.show_more_element.classList.add('_hidden');
        }

        this.rearrangeDelimiters();

        itemElements = this.mobile_menu_item_elements;
        Array.prototype.forEach.call(itemElements, (element, index) => {
            if (index < count) {
                element.classList.add('_hidden');
            }
            else {
                element.classList.remove('_hidden');
            }
        });

        this.rearrangeDelimiters(itemElements);
    }
};

