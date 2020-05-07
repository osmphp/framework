import BaseItem from "../Item";

export default class Item extends BaseItem {
    get mobile_menu() {
        return this.menu.mobile_menu;
    }

    get mobile_menu_item() {
        return this.mobile_menu.getItem(this.name);
    }

    get mobile_menu_events() {
        return {};
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

    withoutNotifyingMobileItem(callback) {
        this.dont_notify_mobile_item = true;
        try {
            callback();
        }
        finally {
            this.dont_notify_mobile_item = false;
        }
    }
};
