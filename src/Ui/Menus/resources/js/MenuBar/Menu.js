import BaseMenu from "../Menu";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";
import trigger from "Osm_Framework_Js/trigger";

export default class Menu extends BaseMenu {
    get events() {
        return Object.assign({}, super.events, {
            'button:click &___show_more': 'onShowMore',
            'menuitem:checked': 'onItemChecked',
        });
    }

    get mobile_menu_events() {
        return {
            'menuitem': 'onMobileItemEvent',
            'menuitem:checked': 'onMobileItemChecked',
        };
    }

    get mobile_menu() {
        let selector = '#' + this.getAliasedId('&___mobile_menu');
        return macaw.get(selector, PopupMenu);
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

    onShowMore(e) {
        this.mobile_menu.open(e.currentTarget, {
            overlap_y: false,
            leftwards: true,
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
