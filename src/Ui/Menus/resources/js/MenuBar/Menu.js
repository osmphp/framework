import BaseMenu from "../Menu";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";
import trigger from "Osm_Framework_Js/trigger";
import cssNumber from "Osm_Framework_Js/cssNumber";

export default class Menu extends BaseMenu {
    get events() {
        return Object.assign({}, super.events, {
            'mouseenter &___show_more': 'onShowMoreMouseEnter',
            'mouseleave &___show_more': 'onShowMoreMouseLeave',
            'button:click &___show_more': 'onShowMoreClick',
            'menuitem:checked': 'onItemChecked',
            'resize window': 'onResize',
        });
    }

    get mobile_menu_events() {
        return {
            'menuitem': 'onMobileItemEvent',
            'menuitem:checked': 'onMobileItemChecked',
            'menu:mouseover': 'onMobileMouseOver',
            'menu:close': 'onMobileClose',
        };
    }

    get item_selector() {
        return '.menu-bar__item';
    }

    get mobile_menu() {
        let selector = '#' + this.getAliasedId('&___mobile_menu');
        return macaw.get(selector, PopupMenu);
    }

    get show_more_element() {
        return this.element.querySelector('.menu-bar__show-more');
    }

    get items_element() {
        return this.element.querySelector('.menu-bar__items');
    }

    get mobile_menu_element() {
        return document.getElementById(this.getAliasedId('&___mobile_menu'));
    }

    get mobile_menu_item_elements() {
        return this.mobile_menu_element.querySelectorAll('.popup-menu__item');
    }

    onAttach() {
        super.onAttach();
        this.onResize();
        requestAnimationFrame(() => {
            this.addEventListeners(this.mobile_menu.element,
                this.mobile_menu_events);
        });
    }

    onDetach() {
        this.removeEventListeners(this.mobile_menu.element,
            this.mobile_menu_events);
        super.onDetach();
    }

    onShowMoreMouseEnter() {
        if (!this.mobile_menu_opened) {
            this.openMobileMenu(false);
        }

        this.mobile_menu_opened = true;
    }

    onShowMoreMouseLeave() {
        this.closeMobileMenu(false);
    }

    onMobileMouseOver(e) {
        if (e.detail.value) {
            this.mobile_menu_opened = true;
        }
        else {
            this.closeMobileMenu(false);
        }
    }

    onShowMoreClick() {
        if (this.mobile_menu_opened) {
            this.closeMobileMenu(true);
            return;
        }

        this.openMobileMenu(true);
    }

    onMobileClose() {
        this.mobile_menu_opened = false;
        this.mobile_menu_opened_with_click = false;
    }

    openMobileMenu(withClick) {
        this.mobile_menu.open(this.show_more_element, {
            overlap_y: false,
            leftwards: true,
        });
        this.mobile_menu_opened = true;
        this.mobile_menu_opened_with_click = withClick;
    }

    closeMobileMenu(withClick) {
        if (this.mobile_menu_opened_with_click && !withClick) {
            return;
        }

        this.mobile_menu_opened = false;
        this.mobile_menu_opened_with_click = false;
        requestAnimationFrame(() => {
            if (!this.mobile_menu_opened) {
                this.mobile_menu.close();
            }
        });
    }

    onMobileItemEvent(e) {
        let data = Object.assign({}, e.detail);

        let event = data.event;
        delete data.event;

        let name = data.name;
        delete data.name;

        trigger(this.element, `menuitem:${event}:${name}`, data);
        data.name = name;
        trigger(this.element, `menuitem:${event}`, data);
        data.event = event;
        trigger(this.element, `menuitem`, data);
    }

    onItemChecked(e) {
        let item = this.mobile_menu.getItem(e.detail.name);
        item.withoutEvents(() => {
            item.checked = e.detail.checked;
        });
    }

    onMobileItemChecked(e) {
        let item = this.getItem(e.detail.name);
        item.withoutEvents(() => {
            item.checked = e.detail.checked;
        });
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
