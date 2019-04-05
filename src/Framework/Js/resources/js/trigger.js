export default function trigger(el, event, data) {
    if (window.CustomEvent) {
        el.dispatchEvent(new CustomEvent(event, {detail: data}));
        return;
    }

    let event_ = document.createEvent('CustomEvent');
    event_.initCustomEvent(event, true, true, data);
    el.dispatchEvent(event_);
};