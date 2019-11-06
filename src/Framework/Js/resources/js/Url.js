import config from "./vars/config";

export default class Url {
    /**
     *
     * @param {string} route
     * @param {Object} query
     */
    generate(route, query = {}) {
        let url = route.substr(route.indexOf(' ') + 1);
        if (/^https?:\/\//i.test(url)) {
            return url;
        }

        return (this.area ? config.base_urls[this.area] : config.base_url)
            + url
            + this.generateQuery(Object.assign(query, config.transient_query));
    }

    generateQuery(query) {
        let result = '';

        if (!query) {
            return result;
        }

        for (let parameter in query) {
            if (!query.hasOwnProperty(parameter)) continue;
            let value = query[parameter];

            if (result) {
                result += '&';
            }
            result += this.encode(parameter);

            if (value === true || value === false) {
                continue;
            }
            result += '=';
            result += this.encode(value);
        }

        if (result) {
            result = '?' + result;
        }

        return result;
    }

    encode(uri) {
        return encodeURIComponent(uri).replace('%20', '+');
    }

    parse(url) {
        let result = document.createElement('a');
        result.href = url;
        return result;
    }

    /**
     * although we render "/login" into form action, browser may add base URL in front of it while it reads
     * it through element.action property. We want exactly what we rendered
     *
     * @param action
     * @returns {*}
     */
    formAction(action) {
        if (!/^https?:\/\//i.test(action)) {
            return action;
        }

        let parsed = this.parse(action);
        return parsed.pathname;
    }

    get parameters() {
        if (!this._parameters) {
            this._parameters = this.parseQuery(location.search);
        }
        return this._parameters;
    }

    parseQuery(query) {
        let result = {};

        query.substr(1).split('&').forEach(query => {
            if (!query) {
                return;
            }

            let pos = query.indexOf('=');
            if (pos == -1) {
                result[query] = true;
                return;
            }

            let key = decodeURIComponent(query.substr(0, pos));
            let value = decodeURIComponent(query.substr(pos + 1));
            if (result[key] === undefined) {
                result[key] = value;
                return;
            }

            if (!Array.isArray(result[key])) {
                result[key] = [result[key]];
            }
            result[key].push(value);
        });

        return result;
    }
};