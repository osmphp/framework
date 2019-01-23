import $ from 'jquery';
import url from './vars/url';

/**
 *
 * @param route
 * @param {Object} options Available options:
 * @param {Object=} options.query - Query parameters
 * @param {Object|Array|boolean|number|string=} options.payload - Data passed as request payload
 * @param {string[]=} options.headers - Additional request headers
 * @returns {Promise<any>}
 */
export default function ajax(route, options = {}) {
    let parameters = {
        method: route.substr(0, route.indexOf(' ')),
        contentType: 'text/plain'
    };

    if (options.payload) {
        parameters.dataType = 'text';
        parameters.data = JSON.stringify(options.payload);
        parameters.processData = false;
        parameters.headers = options.headers || {};
    }

    return Promise.resolve($.ajax(url.generate(route, options.query), parameters));
}