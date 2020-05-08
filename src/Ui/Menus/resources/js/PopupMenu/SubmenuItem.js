import Item from "../Item";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";

export default class SubmenuItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'mouseenter': 'onMouseEnter',
            'mouseleave': 'onMouseLeave',
            'click': 'onClick',
        });
    }

    get submenu() {
        let selector = '#' + this.getAliasedId('&___submenu');
        return macaw.get(selector, PopupMenu);
    }

    get menu_events() {
        return {
            'menu:close': 'onMenuClose',
        };
    }

    get submenu_events() {
        return {
            'menu:mouseover': 'onSubmenuMouseOver',
            'menuitem': 'onMenuItemEvent',
        };
    }

    onAttach() {
        super.onAttach();
        requestAnimationFrame(() => {
            this.addEventListeners(this.menu.element, this.menu_events);
            this.addEventListeners(this.submenu.element, this.submenu_events);
        });
    }

    onDetach() {
        this.removeEventListeners(this.submenu.element, this.submenu_events);
        this.removeEventListeners(this.menu.element, this.menu_events);
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

    onSubmenuMouseOver(e) {
        this.menu.mouseover += e.detail.delta;
        if (e.detail.value) {
            this.opened = true;
        }
        else {
            this.close(false);
        }
    }

    open(withClick) {
        this.submenu.open(this.element, {
            leftwards: this.model.leftwards,
            upwards: this.model.upwards,
            overlap_x: false,
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

    onMenuClose() {
        if (this.opened) {
            this.close(true);
        }
    }

    onMenuItemEvent(e) {
        let data = Object.assign({}, e.detail);

        let event = data.event;
        delete data.event;

        let name = `${this.name}.${data.name}`;
        delete data.name;

        this.trigger(event, data, name);
        this.menu.close();
    }
};
