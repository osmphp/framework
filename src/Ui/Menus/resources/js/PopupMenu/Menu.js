import Positioning from "./Positioning";
import MouseHandling from "./MouseHandling";
import forEachParentElement from "Osm_Framework_Js/forEachParentElement";
import isScrollable from "Osm_Framework_Js/isScrollable";
import macaw from "Osm_Framework_Js/vars/macaw";
import Detacher from "Osm_Framework_Js/Detacher";
import BaseMenu from "../Menu";
import trigger from "Osm_Framework_Js/trigger";

export default class Menu extends BaseMenu {
    get events() {
        return Object.assign({}, super.events, {
            'click document': 'mouse_handling.onDocumentClick',
            'resize window': 'onResizeOrScroll',
            'scroll window': 'onResizeOrScroll',
            'mouseenter': 'onMouseEnter',
            'mouseleave': 'onMouseLeave',
        });
    }

    get item_selector() {
        return '.popup-menu__item';
    }

    get scrollable_parent_events() {
        return {
            'scroll': 'onResizeOrScroll'
        };
    }

    get mouse_handling() {
        if (!this._mouse_handling) {
            this._mouse_handling = new MouseHandling(this);
        }
        return this._mouse_handling;
    }

    get anchor_element() {
        return this.model.anchor_element;
    }

    addListenerToScrollableParents() {
        forEachParentElement(this.model.anchor_element, element => {
            if (isScrollable(element)) {
                this.addEventListeners(element, this.scrollable_parent_events);
            }
        });
    }

    removeListenerFromScrollableParents() {
        forEachParentElement(this.model.anchor_element, element => {
            if (isScrollable(element)) {
                this.removeEventListeners(element, this.scrollable_parent_events);
            }
        });
    }

    onAttach() {
        super.onAttach();
        macaw.attachControllerToElement(Detacher, this.element.parentNode, null,
            {element_to_be_detached: this.element});
        document.body.appendChild(this.element);
        this._mouseover = 0;
        this._last_mouseover = 0;
    }

    onDetach() {
        this.close();
        super.onDetach();
    }

    open(anchorElement, options = {}) {
        this.model = Object.assign({
            anchor_element: anchorElement,
            leftwards: false,
            upwards: false,
            overlap_x: true,
            overlap_y: true,
            opening: true,
            opened: true,
        }, options);

        this.$element.show();
        this.align();

        if (this.model.opened) { // after align() call menu can get closed
            this.addListenerToScrollableParents();
        }

        requestAnimationFrame(() => {
            delete this.model.opening;
        });
    }

    align() {
        // create new instance every time we need to align menu.
        // It uses intermediate calculation results as properties so it
        // is should not be reused
        (new Positioning(this)).align();
    }

    close() {
        if (!this.model.opened) {
            return;
        }

        this.removeListenerFromScrollableParents();
        delete this.model.anchor_element;
        delete this.model.opened;
        this.$element.hide();
        trigger(this.element, 'menu:close');
    }

    onResizeOrScroll() {
        if (!this.model.opened) {
            return;
        }

        if (this.model.parent_menu) {
            return;
        }

        this.align();
    }

    onMouseEnter() {
        this.mouseover++;
    }

    onMouseLeave() {
        this.mouseover--;
    }

    get mouseover() {
        return this._mouseover;
    }

    set mouseover(value) {
        this._mouseover = value;

        if (this._last_mouseover === this._mouseover) {
            return;
        }

        this.element.dispatchEvent(new CustomEvent('menu:mouseover', {
            detail: {
                value: this._mouseover > 0,
                delta: this._mouseover - this._last_mouseover,
            },
        }));

        this._last_mouseover = this._mouseover;
    }
};
