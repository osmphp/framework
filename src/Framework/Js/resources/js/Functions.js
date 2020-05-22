import url from './vars/url';
import osm_t from "./osm_t";

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
     * @returns {Promise}
     */
    ajax(route, options = {}) {
        let parameters = {
            method: route.substr(0, route.indexOf(' ')),
        };

        parameters.headers = options.headers || {};

        if (options.payload !== undefined) {
            parameters.headers['Content-Type'] = 'application/json';
            parameters.body = JSON.stringify(options.payload);
        }

        return fetch(url.generate(route, options.query), parameters)
            .then(response => {
                return response.ok
                    ? (response.headers.get("content-type") == 'application/json'
                        ? response.json()
                        : response.text().then(text => {
                            if (!text.length) {
                                return Promise.reject(new Error(osm_t(
                                    "Request processing was interrupted.")));
                            }

                            return Promise.resolve(text);
                        })
                    )
                    : Promise.reject(response);
            });
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