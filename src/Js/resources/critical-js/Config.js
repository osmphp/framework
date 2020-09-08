import merge from './merge';

/**
 * @property {string} base_url
 * @property {Array} transient_query
 * @property {string[]} translations
 * @property {number} close_snack_bars_after
 */
export default class Config {
    merge(data) {
        return merge(this, data);
    }
};