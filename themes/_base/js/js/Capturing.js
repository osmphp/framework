export default class Capturing {
    capturing_elements = [];
    capturing = false;
    events = {
        'mousedown': 'onMouseDown',
        'mouseup': 'onMouseUp',
        'click': 'onClick',
        'dblclick': 'onDoubleClick',
        'focus': 'onFocus',
    };

    /**
     * @param {HTMLElement} element
     */
    capture(element) {
        if (!this.capturing) {
            this.listen();
            this.capturing = true;
        }

        let focused = document.activeElement &&
            document.activeElement instanceof HTMLElement
                ? document.activeElement : null;

        if (focused) {
            focused.blur();
        }

        this.capturing_elements.push({element, focused});
        element.focus();
    }

    release() {
        let capture = this.capturing_elements.pop();

        if (capture.focused) {
            capture.focused.focus();
        }
    }

    /**
     * @returns {HTMLElement|null}
     */
    get capturing_element() {
        return this.capturing_elements.length
            ? this.capturing_elements[this.capturing_elements.length - 1].element
            : null;
    }

    listen() {
        for (let type in this.events) {
            if (!this.events.hasOwnProperty(type)) {
                continue;
            }

            document.addEventListener(type, e => {
                if (this.outside(e)) {
                    this[this.events[type]](e);
                }
            }, true);
        }

    }

    /**
     *
     * @param {Event} e
     * @returns {boolean}
     */
    outside(e) {
        return this.capturing_element &&
            !this.capturing_element.contains(e.target);
    }

    onMouseDown(e) {
        e.stopPropagation();
    }

    onMouseUp(e) {
        e.stopPropagation();
    }

    onClick(e) {
        e.stopPropagation();
        this.capturing_element.dispatchEvent(new CustomEvent('outside-click', {
            detail: {e}
        }));
    }

    onDoubleClick(e) {
        e.stopPropagation();
    }

    onFocus(e) {
        e.stopPropagation();
        this.capturing_element.focus();
    }
}