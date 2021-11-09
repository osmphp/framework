export default class Elevation {
    /**
     * @param {HTMLElement} element
     */
    front(element) {
        let marker = document.createElement('div');
        marker.style.display = 'none';

        element.osm_marker = marker;
        element.insertAdjacentElement('afterend', marker);
        document.body.insertAdjacentElement('beforeend', element);
    }

    /**
     * @param {HTMLElement} element
     */
    back(element) {
        if (!element.osm_marker) {
            return;
        }

        element.osm_marker.insertAdjacentElement('afterend', element);
        element.osm_marker.remove();
        delete element.osm_marker;
    }
}