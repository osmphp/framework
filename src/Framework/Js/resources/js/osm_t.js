import config from './vars/config';

export default function osm_t(text, parameters = {}) {
    if (config.translations && config.translations[text]) {
        text = config.translations[text];
    }
    else {
        console.log("Translation for '" + text + "' not provided by server side");
    }

    Object.keys(parameters).sort((a, b) => b.length - a.length).forEach((value, parameter) => {
        text = text.replace(new RegExp('\\:' + parameter, 'g'), value);
    });

    return text;
}