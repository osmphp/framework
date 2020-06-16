import config from "Osm_Framework_Js/vars/config";

export default function osmDebugViews() {
    const STYLES = '/styles.css';
    const DEBUG_VIEWS = '/Osm_Framework_Views/debug-views.css';

    let linkElements = document.head.querySelectorAll(
        'link[rel="stylesheet"]');

    let baseUrl, debugViewsElement;

    Array.prototype.forEach.call(linkElements, element => {
        let href = element.getAttribute('href');

        if (!href) {
            return;
        }

        let pos = href.indexOf(STYLES);
        if (pos != -1) {
            baseUrl = href.substr(0, pos);
            return;
        }

        if (href.indexOf(DEBUG_VIEWS) != -1) {
            debugViewsElement = element;
            return;
        }
    });

    if (!baseUrl) {
        return;
    }

    if (debugViewsElement) {
        return;
    }

    debugViewsElement = document.createElement('link');
    debugViewsElement.rel = 'stylesheet';
    debugViewsElement.href = `${baseUrl}${DEBUG_VIEWS}`;

    document.head.appendChild(debugViewsElement);
};