import Controller from "Manadev_Framework_Js/Controller";
import Positioning from "./Positioning";
import MouseHandling from "./MouseHandling";
import forEachParentElement from "Manadev_Framework_Js/forEachParentElement";
import isScrollable from "Manadev_Framework_Js/isScrollable";
import macaw from "Manadev_Framework_Js/vars/macaw";
import Detacher from "Manadev_Framework_Js/Detacher";
import ItemApi from "./ItemApi";

export default class PopupMenu extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click document': 'mouse_handling.onDocumentClick',
            'resize window': 'onResizeOrScroll',
            'scroll window': 'onResizeOrScroll'
        });
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
    }

    onDetach() {
        this.close();
        super.onDetach();
    }

    open(anchorElement, options) {
        this.model = Object.assign({
            anchor_element: anchorElement,
            leftwards: false,
            upwards: false,
            overlap_x: true,
            overlap_y: true,
            opening: true,
            opened: true
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
        // create new instance every time we need to align menu. It uses intermediate calculation results
        // as properties so it is should not be reused
        let positioning = new Positioning(this);

        positioning.align();
    }

    close() {
        if (!this.model.opened) {
            return;
        }

        this.removeListenerFromScrollableParents();
        delete this.model.anchor_element;
        delete this.model.opened;
        this.$element.hide();
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

    item(name) {
        let element = document.getElementById(this.getAliasedId(`&__${name}__item`));
        if (!element) {
            throw new Error(`Menu item '${name}' not found`);
        }

        return new ItemApi(name, element);
    }
};
