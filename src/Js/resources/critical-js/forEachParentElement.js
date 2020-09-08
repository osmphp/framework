/**
 * Iterates through parent DOM elements of given element and invokes callback for each parent element. If callback
 * returns value which is not false, null, 0, '' or NaN, this function stop iterating and returns last iterated parent
 * element. Otherwise, this function returns null.
 *
 * This function is useful both for iterating through all parents (in this case just don't return any value in
 * callback function) or for finding a parent element matching some criteria (in this case return true in callback for
 * parent matching the criteria).
 *
 * @param {HTMLElement} element
 * @param {function} callback
 * @returns {*}
 */
export default function forEachParentElement(element, callback) {
    for (let parent = element.parentNode; parent != null; parent = parent.parentNode) {
        if (parent.tagName.toLowerCase() == 'body') {
            return;
        }

        if (parent.tagName.toLowerCase() == 'html') {
            return;
        }

        let result = callback(parent);
        if (result) {
            return result;
        }
    }
};
