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
     *      makes sense if Osm_Ui_SnackBars module is installed
     * @returns {Promise<any>}
     */
    ajax(route, options = {}) {
        let parameters = {
            method: route.substr(0, route.indexOf(' ')),
        };

        if (options.payload !== undefined) {
            parameters.contentType = 'text/plain';
            parameters.dataType = 'text';
            parameters.data = JSON.stringify(options.payload);
            parameters.processData = false;
        }
        else if (options.data !== undefined) {
            parameters.data = options.data;
        }

        parameters.headers = options.headers || {};

        return Promise.resolve($.ajax(url.generate(route, options.query), parameters));
    }

    area(name, callback) {
        let area = url.area;
        url.area = name;

        try {
            return callback();
        }
        finally {
            url.area = area;
        }
    }
};