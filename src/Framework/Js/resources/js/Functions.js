import $ from 'jquery';
import url from './vars/url';

export default class Functions {
    /**
     *
     * @param route
     * @param {Object} options Available options:
     * @param {Object=} options.query - Query parameters
     * @param {Object|Array|boolean|number|string=} options.payload - Data passed as request payload
     * @param {string[]=} options.headers - Additional request headers
     * @param {string=} options.snackbar_message - Enables showing request progress and error handling in
     *      snack bar. While request is being processed, this message is shown in snackbar. This option only
     *      makes sense if Manadev_Ui_SnackBars module is installed
     * @returns {Promise<any>}
     */
    ajax(route, options = {}) {
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
};