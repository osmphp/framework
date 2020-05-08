import BaseMenu from "../Menu";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";
import trigger from "Osm_Framework_Js/trigger";

export default class Menu extends BaseMenu {
    get events() {
        return Object.assign({}, super.events, {
            'mouseenter &___show_more': 'onShowMoreMouseEnter',
            'mouseleave &___show_more': 'onShowMoreMouseLeave',
            'button:click &___show_more': 'onShowMoreClick',
            'menuitem:checked': 'onItemChecked',
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

    get mobile_menu() {
        let selector = '#' + this.getAliasedId('&___mobile_menu');
        return macaw.get(selector, PopupMenu);
    }

    get show_more_element() {
        return document.getElementById(this.getAliasedId('&___show_more'));
    }

    onAttach() {
        super.onAttach();
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
};
