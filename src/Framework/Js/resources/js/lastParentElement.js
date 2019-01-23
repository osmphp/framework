import forEachParentElement from './forEachParentElement';


/**
 * This function is alias of 'forEachParentElement' function.
 *
 * @param {HTMLElement} element
 * @param {function} callback
 * @returns {*}
 */
export default function lastParentElement(element, callback) {
    let result = undefined;

    forEachParentElement(element, element => {
        if (!callback || callback(element)) {
            result = element;
        }
    });

    return result;
};
