import Action from "Osm_Framework_Js/Action";
import doRectanglesIntersect from "Osm_Framework_Js/doRectanglesIntersect";
import firstParentElement from "Osm_Framework_Js/firstParentElement";
import isScrollable from "Osm_Framework_Js/isScrollable";
import getViewPortRect from "Osm_Ui_Aba/getViewPortRect";
import cssNumber from "Osm_Framework_Js/cssNumber";

export default class Positioning extends Action {
    get window() {
        if (!this._window) {
            this._window = this.rect(getViewPortRect());
        }

        return this._window;
    }
    get anchor() {
        if (!this._anchor) {
            let rect = this.model.anchor_element.getBoundingClientRect();
            let style = getComputedStyle(this.model.anchor_element);
            this._anchor = this.rect({
                left: rect.left - cssNumber(style.marginLeft),
                top: rect.top - cssNumber(style.marginTop),
                right: rect.right + cssNumber(style.marginRight),
                bottom: rect.bottom + cssNumber(style.marginBottom),
            });
        }

        return this._anchor;
    }
    get body() {
        if (!this._body) {
            let relativeParent = document.body;
            let rect = relativeParent.getBoundingClientRect();
            let style = getComputedStyle(relativeParent);
            this._body = this.rect({
                left: rect.left - cssNumber(style.marginLeft),
                top: rect.top - cssNumber(style.marginTop),
                right: rect.right + cssNumber(style.marginRight),
                bottom: rect.bottom + cssNumber(style.marginBottom),
            });
        }

        return this._body;
    }
    get menu() {
        if (!this._menu) {
            this._menu = this.rect(this.element.getBoundingClientRect());
        }

        return this._menu;
    }
    get viewport() {
        if (!this._viewport) {
            this._viewport = this.rect(getViewPortRect('popup-menu'));
        }

        return this._viewport;
    }
    get first_item() {
        if (!this._first_item) {
            let element = this.$element.find('.popup-menu__item:not(.-hidden)').first()[0];
            this._first_item = this.rect(element.getBoundingClientRect());
        }

        return this._first_item;
    }

    get last_item() {
        if (!this._last_item) {
            let element = this.$element.find('.popup-menu__item:not(.-hidden)').last()[0];
            this._last_item = this.rect(element.getBoundingClientRect());
        }

        return this._last_item;
    }

    align() {
        if (!this.isScrolledIntoView(this.model.anchor_element, this.anchor)) {
            this.controller.close();
            return;
        }

        this.$element.css({
            left: (this.alignX() + window.scrollX) + 'px',
            top: (this.alignY() + window.scrollY) + 'px'
        });

        if (!this.model.child_menu) {
            return;
        }

        if (this.leftwards !== undefined) {
            this.model.child_menu.model.leftwards = this.leftwards;
        }
        if (this.upwards !== undefined) {
            this.model.child_menu.model.upwards = this.upwards;
        }
        this.model.child_menu.align();
    }

    isScrolledIntoView(element, rect) {
        if (!doRectanglesIntersect(rect, this.window)) {
            return false;
        }

        let scrollableParentExists = false;
        let scrollableVisibleParentElement = firstParentElement(element, element => {
            if (!isScrollable(element)) {
                return false;
            }

            scrollableParentExists = true;
            return doRectanglesIntersect(rect, element.getBoundingClientRect());
        });

        return !scrollableParentExists || scrollableVisibleParentElement;
    }

    alignX() {
        if (this.menu.width > this.viewport.width) {
            return this.viewport.left;
        }

        return this.model.overlap_x
            ? (this.model.leftwards ? this.alignXOverlappingLeftwards() : this.alignXOverlappingRightwards())
            : (this.model.leftwards ? this.alignXLeftwards() : this.alignXRightwards());
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      w - menu left
     *      W - menu right
     *      a - anchor left
     *      A - anchor right
     *      v - viewport left
     *      V - viewport right
     *
     *               w...a.....A...W
     * 1          v.......V
     * 2               v.......V
     * 3                 v.......V
     * 4                      v.......V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignXLeftwards() {
        if (this.anchor.left - this.menu.width >= this.viewport.left) {
            return this.anchor.left - this.menu.width; // [1] -> w
        }

        if (this.anchor.left >= this.viewport.left) {
            this.leftwards = false;
            return this.viewport.left; // [2] -> v, rightwards
        }

        if (this.anchor.right + this.menu.width <= this.viewport.right) {
            this.leftwards = false;
            return this.anchor.right; // [4] -> A, rightwards
        }

        return this.viewport.right - this.menu.width; // [3] -> V - w
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      w - menu left
     *      W - menu right
     *      a - anchor left
     *      A - anchor right
     *      v - viewport left
     *      V - viewport right
     *
     *               w...a.....A...W
     * 1          v.......V
     * 2               v.......V
     * 3                 v.......V
     * 4                      v.......V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignXRightwards() {
        if (this.anchor.right + this.menu.width <= this.viewport.right) {
            return this.anchor.right; // [4] -> A
        }

        if (this.anchor.right <= this.viewport.right) {
            this.leftwards = true;
            return this.viewport.right - this.menu.width; // [3] -> V - w, leftwards
        }

        if (this.anchor.left - this.menu.width >= this.viewport.left) { // [1] -
            this.leftwards = true;
            return this.anchor.left - this.menu.width; // [1] -> w, leftwards
        }

        return this.viewport.left; // [2] -> v
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      w - menu left
     *      W - menu right
     *      a - anchor left
     *      A - anchor right
     *      v - viewport left
     *      V - viewport right
     *
     *             a.w.....W.A
     * 1       v...........V
     * 2           v...........V
     * 3                v...........V
     *
     *             w.a.....A.W
     * 1      v...........V
     * 2          v...........V
     * 3               v...........V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignXOverlappingLeftwards() {
        if (this.anchor.right > this.viewport.right) {
            return this.viewport.right - this.menu.width; // [1] -> V - w
        }

        if (this.anchor.right - this.menu.width >= this.viewport.left) {
            return this.anchor.right - this.menu.width; // [2] -> w
        }

        this.leftwards = false;
        return this.viewport.left; // [3] -> v, rightwards
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      w - menu left
     *      W - menu right
     *      a - anchor left
     *      A - anchor right
     *      v - viewport left
     *      V - viewport right
     *
     *             a.w.....W.A
     * 1             v...........V
     * 2          v...........V
     * 3       v...........V
     *
     *             w.a.....A.W
     * 1              v...........V
     * 2          v...........V
     * 3       v...........V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignXOverlappingRightwards() {
        if (this.anchor.left < this.viewport.left) {
            return this.viewport.left; // [1] -> v
        }

        if (this.anchor.left + this.menu.width <= this.viewport.right) {
            return this.anchor.left; // [2] -> a
        }

        this.leftwards = true;
        return this.viewport.right - this.menu.width; // [3] -> V - w, leftwards
    }

    alignY() {
        if (this.menu.height > this.viewport.height) {
            return this.viewport.top;
        }

        return this.model.overlap_y
            ? (this.model.upwards ? this.alignYOverlappingUpwards() : this.alignYOverlappingDownwards())
            : (this.model.upwards ? this.alignYUpwards() : this.alignYDownwards());
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      h - menu top
     *      H - menu bottom
     *      a - anchor top
     *      A - anchor bottom
     *      v - viewport top
     *      V - viewport bottom
     *
     *               h...a.....A...H
     * 1          v.......V
     * 2               v.......V
     * 3                 v.......V
     * 4                      v.......V
     *
     * @returns {int} Calculated menu top. Returns negative if menu cascade should change direction in child menus
     */
    alignYUpwards() {
        if (this.anchor.top - this.menu.height >= this.viewport.top) {
            return this.anchor.top - this.menu.height; // [1] -> h
        }

        if (this.anchor.top >= this.viewport.top) {
            this.upwards = false;
            return this.viewport.top; // [2] -> v, downwards
        }

        if (this.anchor.bottom + this.menu.height <= this.viewport.bottom) {
            this.upwards = false;
            return this.anchor.bottom; // [4] -> A, downwards
        }

        return this.viewport.bottom - this.menu.height; // [3] -> V - h
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      h - menu top
     *      H - menu bottom
     *      a - anchor top
     *      A - anchor bottom
     *      v - viewport top
     *      V - viewport bottom
     *
     *               h...a.....A...H
     * 1          v.......V
     * 2               v.......V
     * 3                 v.......V
     * 4                      v.......V
     *
     * @returns {int} Calculated menu top. Returns negative if menu cascade should change direction in child menus
     */
    alignYDownwards() {
        if (this.anchor.bottom + this.menu.height <= this.viewport.bottom) {
            return this.anchor.bottom; // [4] -> A
        }

        if (this.anchor.bottom <= this.viewport.bottom) {
            this.upwards = true;
            return this.viewport.bottom - this.menu.height; // [3] -> V - h, upwards
        }

        if (this.anchor.top - this.menu.height >= this.viewport.top) {
            this.upwards = true;
            return this.anchor.top - this.menu.height; // [1] -> w, upwards
        }

        return this.viewport.top; // [2] -> v
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      h - menu top
     *      H - menu bottom
     *      a - anchor top
     *      A - anchor bottom
     *      v - viewport top
     *      V - viewport bottom
     *
     *             a.h..m..H.A
     * 1      v...........V
     * 2          v...........V
     * 3               v...........V
     *
     *             h.a.....A.H
     * 1      v...........V
     * 2          v...........V
     * 3               v...........V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignYOverlappingUpwards() {
        // middle of last item should be equal to anchor middle.
        let y = this.anchor.top + 0.5 * this.anchor.height;

        let lastMenuItemMiddle = this.last_item.top + 0.5 * this.last_item.height;
        y -= -(lastMenuItemMiddle - this.menu.top);

        if (y + this.menu.height > this.viewport.bottom) {
            return this.viewport.bottom - this.menu.height; // [1] -> V - h
        }
        if (y >= this.viewport.top) {
            return y; // [2] -> A'
        }

        this.upwards = false;
        return this.viewport.top; // [3] -> v, downwards
    }

    /**
     * The following notation depicts all possible situations. All in window coordinates:
     *      h - menu top
     *      H - menu bottom
     *      a - anchor top
     *      A - anchor bottom
     *      v - viewport top
     *      V - viewport bottom
     *
     *             a.h..m..H.A
     * 1               v...........V
     * 2          v...........V
     * 3     v...........V
     *
     *             h.a.....A.H
     * 1               v...........V
     * 2          v...........V
     * 3     v...........V
     *
     * @returns {int} Calculated menu left. Returns negative if menu cascade should change direction in child menus
     */
    alignYOverlappingDownwards() {
        // middle of first item should be equal to anchor middle.
        let y = this.anchor.top + 0.5 * this.anchor.height;

        let firstMenuItemMiddle = this.first_item.top + 0.5 * this.first_item.height;
        y -= firstMenuItemMiddle - this.menu.top;

        if (y < this.viewport.top) {
            return this.viewport.top ; // [1] -> v
        }

        if (y + this.menu.height <= this.viewport.bottom) {
            return y; // [2] -> a'
        }

        this.upwards = true;
        return this.viewport.bottom - this.menu.height; // [3] -> V - h, upwards

    }

    rect(rect) {
        rect.width = rect.right - rect.left;
        rect.height = rect.bottom - rect.top;
        return rect;
    }
}