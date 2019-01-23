export default class Object_ {
    /**
     * @param {Object=} data
     */
    constructor(data) {
        this.set(data);
    }

    set(data) {
        data = data || {};

        for (let property in data) {
            if (!data.hasOwnProperty(property)) continue;

            this[property] = data[property];
        }
    }
}
