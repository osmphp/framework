import forEachParentElement from './forEachParentElement';


/**
 * This function is alias of 'forEachParentElement' function.
 *
 * @param {HTMLElement} element
 * @param {function} callback
 * @returns {*}
 */
export default function firstParentElement(element, callback) {
    return forEachParentElement(element, element => !callback || callback(element) === true ? element : undefined);
};
