export default class Controller {
    /**
     * @type {HTMLElement}
     */
    element;

    /**
     * @type {object}
     */
    options = {};

    bound_event_listeners = {};

    /**
     * @param {HTMLElement} element
     * @param {object} options
     */
    constructor(element, options) {
        this.element = element;
        this.options = options;
    }

    get events() {
        return {};
    }

    onAttaching() {
    }

    onAttached() {
        this.addEventListeners(this.element, this.events);
    }

    addEventListeners(element, events) {
        for (let event in events) {
            if (!events.hasOwnProperty(event)) {
                continue;
            }

            let listener = events[event];
            let parsed = this.parseEventNameAndSelector(element, event);
            let key = parsed.prefix + listener;

            if (!this.bound_event_listeners[key]) {
                let boundListener = this.bindListener(listener);
                this.bound_event_listeners[key] =
                    parsed.throttle
                        ? this.createThrottledListener(boundListener) : (
                    parsed.debounce === true
                        ? this.createDebouncedPerAnimationFrameListener(boundListener) : (
                    parsed.debounce
                        ? this.createDebouncedListener(boundListener, parsed.debounce) : (
                    parsed.live
                        ? this.createLiveListener(boundListener, parsed.selector) :
                    boundListener)));
            }

            for (let element of parsed.matching_elements) {
                element.addEventListener(parsed.event,
                    this.bound_event_listeners[key], parsed.capture);
            }
        }
    }

    parseEventNameAndSelector(element, eventNameAndSelector) {
        let result = { throttle: false, capture: false, debounce: false, prefix: ''};
        let flags = ['throttle', 'capture', 'debounce', 'live'];
        let pos;

        for (let i = 0; i < flags.length; i++) {
            let prefix = flags[i] + ':';
            if (!eventNameAndSelector.startsWith(prefix)) {
                continue;
            }
            result[flags[i]] = true;
            eventNameAndSelector = eventNameAndSelector.substr(prefix.length);
            if (/^\d+:/.test(eventNameAndSelector)) {
                pos = eventNameAndSelector.indexOf(':');
                result[flags[i]] = parseInt(eventNameAndSelector.substr(0, pos));
                eventNameAndSelector = eventNameAndSelector.substr(pos + 1);
            }
            result.prefix = flags[i] + ':' + result.prefix;
            break;
        }

        pos = eventNameAndSelector.indexOf(' ');

        if (pos !== -1) {
            result.event = eventNameAndSelector.substr(0, pos);

            let selector = eventNameAndSelector.substr(pos + 1);
            result.selector = selector;
            result.matching_elements =
                result.live ? [element] : (
                selector === 'broadcast' ? [] : (
                selector === 'window' ? [window] : (
                selector === 'document' ? [document] : (
                selector.startsWith('&') ? this.queryAliasedElement(selector) :
                this.element.querySelectorAll(selector)))));
        }
        else {
            result.event = eventNameAndSelector;
            result.matching_elements = [element];
        }

        return result;
    }

    queryAliasedElement(selector) {
        let element = document.getElementById(this.getAliasedId(selector));
        return element ? [element] : [];
    }

    getAliasedId(alias) {
        return this.element.id
            ? this.element.id + alias.substr(1)
            : alias.substr(3);
    }

    bindListener(listener) {
        let pos = listener.indexOf('.');
        if (pos === -1) {
            return this[listener].bind(this);
        }

        let action = listener.substr(0, pos);
        let method = listener.substr(pos + 1);

        return this[action][method].bind(this[action]);
    }

    createThrottledListener(callback) {
        let calledInCurrentAnimationFrame = false;

        return () => {
            if (calledInCurrentAnimationFrame) {
                return;
            }
            calledInCurrentAnimationFrame = true;
            callback.apply(this, arguments);

            requestAnimationFrame(() => {
                calledInCurrentAnimationFrame = false;
            });
        }
    }

    createDebouncedPerAnimationFrameListener(callback) {
        let calledInCurrentAnimationFrame = false;
        let debouncing = false;

        return function() {
            function onNextAnimationFrame() {
                if (!calledInCurrentAnimationFrame) {
                    callback.apply(this, arguments);
                    debouncing = false;
                }
                else {
                    calledInCurrentAnimationFrame = false;
                    requestAnimationFrame(onNextAnimationFrame);
                }
            }

            calledInCurrentAnimationFrame = true;
            if (debouncing) {
                return;
            }

            debouncing = true;
            requestAnimationFrame(onNextAnimationFrame);
        }
    }

    createDebouncedListener(callback, timeout) {
        let calledInCurrentTick = false;
        let debouncing = false;

        return function() {
            function onNextTick() {
                if (!calledInCurrentTick) {
                    callback.apply(this, arguments);
                    debouncing = false;
                }
                else {
                    calledInCurrentTick = false;
                    setTimeout(onNextTick, timeout);
                }
            }

            calledInCurrentTick = true;
            if (debouncing) {
                return;
            }

            debouncing = true;
            setTimeout(onNextTick, timeout);
        }
    }

    createLiveListener(callback, selector) {
        if (!selector) {
            return callback;
        }

        return e => {
            let parentElement = this.getFirstParentElement(e.target,
                element => element.matches(selector));
            if (parentElement) {
                e.liveTarget = parentElement;
                return callback(e);
            }
        };
    }

    getFirstParentElement(element, callback) {
        return this.forEachParentElement(element, element =>
            !callback || callback(element) === true ? element : undefined);
    }

    forEachParentElement(element, callback) {
        for (let parent = element.parentNode; parent != null;
            parent = parent.parentNode)
        {
            if (parent.tagName.toLowerCase() === 'body') {
                return;
            }

            if (parent.tagName.toLowerCase() === 'html') {
                return;
            }

            let result = callback(parent);
            if (callback(parent)) {
                return result;
            }
        }
    }
}