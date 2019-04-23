import callOncePerAnimationFrame from "./callOncePerAnimationFrame";
import debounceForAnimationFrame from "./debounceForAnimationFrame";
import debounce from "./debounce";
import $ from 'jquery';

export default class Controller {
    constructor(element, viewModel, model) {
        this.element = element;
        this.view_model = viewModel;
        this.model = this.view_model ? this.view_model.model : Object.assign({}, model) || {};
        this.bound_event_listeners = {};
    }

    get id() {
        return this.element.id || this.element.getAttribute('data-id');
    }

    get events() {
        return {};
    }

    get $element() {
        return $(this.element);
    }

    get alias_base() {
        return this.element.id;
    }

    onAttach() {
        this.addEventListeners(this.element, this.events);
    }

    onDetach() {
        this.removeEventListeners(this.element, this.events);

        if (this.view_model) {
            this.view_model.onDetach();
        }
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
                this.bound_event_listeners[key] = parsed.throttle
                    ? callOncePerAnimationFrame(this.bindListener(listener))
                    : (parsed.debounce === true
                        ? debounceForAnimationFrame(this.bindListener(listener))
                        : (parsed.debounce
                            ? debounce(this.bindListener(listener), parsed.debounce)
                            : this.bindListener(listener)
                        )
                    );
            }

            Array.prototype.forEach.call(parsed.matching_elements, element => {
                element.addEventListener(parsed.event, this.bound_event_listeners[key], parsed.capture);
            });
        }
    }

    removeEventListeners(element, events) {
        for (let event in events) {
            if (!events.hasOwnProperty(event)) {
                continue;
            }

            let listener = events[event];
            let parsed = this.parseEventNameAndSelector(element, event);
            let key = parsed.prefix + listener;

            Array.prototype.forEach.call(parsed.matching_elements, element => {
                element.removeEventListener(parsed.event, this.bound_event_listeners[key]);
            });
        }
    }

    parseEventNameAndSelector(element, eventNameAndSelector) {
        let result = { throttle: false, capture: false, debounce: false, prefix: ''};
        let flags = ['throttle', 'capture', 'debounce'];
        let pos;

        for (let i = 0; i < flags.length; i++) {
            let prefix = flags[i] + ':';
            if (!eventNameAndSelector.startsWith(prefix)) {
                continue;
            }
            result[flags[i]] = true;
            eventNameAndSelector = eventNameAndSelector.substr(prefix.length);
            if (/^\d+\:/.test(eventNameAndSelector)) {
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
            result.matching_elements =
                selector == 'broadcast' ? [] : (
                selector == 'window' ? [window] : (
                selector == 'document' ? [document] : (
                selector.startsWith('&') ? this.queryAliasedElement(selector) :
                this.element.querySelectorAll(selector))));
        }
        else {
            result.event = eventNameAndSelector;
            result.matching_elements = [element];
        }

        return result;
    }

    bindListener(listener) {
        let pos = listener.indexOf('.');
        if (pos == -1) {
            return this[listener].bind(this);
        }

        let action = listener.substr(0, pos);
        let method = listener.substr(pos + 1);
        return this[action][method].bind(this[action]);
    }

    getAliasedId(alias) {
        if (!this.element.id) {
            return alias.substr(3);
        }
        return this.alias_base + alias.substr(1);
    }

    queryAliasedElement(selector) {
        let element = document.getElementById(this.getAliasedId(selector));
        return element ? [element] : [];
    }
};