import config from './vars/config';
import formatString from "./formatString";

export default function osm_t(text, parameters = {}) {
    if (config.translations && config.translations[text]) {
        text = config.translations[text];
    }
    else {
        console.log("Translation for '" + text + "' not provided by server side");
    }

    return formatString(text, parameters);
}