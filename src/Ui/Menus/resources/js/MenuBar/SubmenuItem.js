import Item from "../Item";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";

export default class SubmenuItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'mouseenter &___button': 'onMouseEnter',
            'mouseleave &___button': 'onMouseLeave',
            'button:click &___button': 'onClick',
        });
    }

    get submenu() {
        let selector = '#' + this.getAliasedId('&___submenu');
        return macaw.get(selector, PopupMenu);
    }

    get submenu_events() {
        return {
            'mouseenter': 'onSubmenuMouseEnter',
            'mouseleave': 'onSubmenuMouseLeave',
            'menuitem': 'onMenuItemEvent',
            'menu:close': 'onSubmenuClose',
        };
    }

    onAttach() {
        super.onAttach();
        requestAnimationFrame(() => {
            this.addEventListeners(this.submenu.element, this.submenu_events);
        });
    }

    onDetach() {
        this.removeEventListeners(this.submenu.element, this.submenu_events);
        super.onDetach();
    }

    onMouseEnter() {
        if (!this.opened) {
            this.open(false);
        }

        this.opened = true;
    }

    onMouseLeave() {
        this.close(false);
    }

    onSubmenuMouseEnter() {
        this.opened = true;
    }

    onSubmenuMouseLeave() {
        this.close(false);
    }

    onSubmenuClose() {
        this.opened = false;
        this.openedWithClick = false;
    }

    open(withClick) {
        this.submenu.open(this.element, {
            overlap_y: false,
        });
        this.opened = true;
        this.openedWithClick = withClick;
    }

    close(withClick) {
        if (this.openedWithClick && !withClick) {
            return;
        }

        this.opened = false;
        this.openedWithClick = false;
        requestAnimationFrame(() => {
            if (!this.opened) {
                this.submenu.close();
            }
        });
    }

    onClick() {
        if (this.opened) {
            this.close(true);
            return;
        }

        this.open(true);

    }

    onMenuItemEvent(e) {
        let data = Object.assign({}, e.detail);

        let event = data.event;
        delete data.event;

        let name = `${this.name}.${data.name}`;
        delete data.name;

        this.trigger(event, data, name);
    }
};
