export default function find(selector) {
    if (selector instanceof HTMLElement) {
        return selector;
    }

    return /^#[A-Za-z0-9_\-]+$/.test(selector)
        ? document.getElementById(selector.substr(1))
        : document.querySelector(selector);
};

