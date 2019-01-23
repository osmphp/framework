import ajax from './ajax';

/**
 * @property {Object.<string, Object>} items Map containing registered HTML templates. Key is HTML template name.
 *      Structure of an item:
 *          {string} html Template HTML
 *          {string} route If html property is empty, URL from which it should be loaded
 *          {Object} query Query parameters, used with route property
 *          {string} id If html property is empty, ID of <script> element containing template HTML
 */
export default class Templates {
    constructor() {
        this.items = {};
    }

    get(name) {
        let item = this.items[name];

        if (!item) {
            let element = document.getElementById(name);
            if (!element) {
                throw new Error("Template '" + name + "' is not defined");
            }

            this.items[name] = item = {
                html: element.innerText.replace(/<@\/script>/i, '</script>')
            };
            return Promise.resolve(item.html);
        }

        if (item.html) {
            return Promise.resolve(item.html);
        }

        if (item.id) {
            let element = document.getElementById(item.id);
            if (!element) {
                throw new Error("Template element '#" + item.id + "' not found");
            }

            return Promise.resolve(item.html = element.innerText.replace(/<@\/script>/i, '</script>'));
        }

        if (!item.route) {
            throw new Error("Template '" + item.id + " should either define ID of element " +
                "containing the template or URL route from where template should be downloaded.");
        }

        return ajax(item.route, {query: item.query})
            .then(html => {
                return item.html = html;
            });
    }

    add(name, item) {
        this.items[name] = item;
        this.get(name)
            .catch(error => {
                // do nothing as Developer Tools->Network and Console will show network errors
            });
    }
};