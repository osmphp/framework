let locale = document.documentElement.getAttribute('lang');
let example = Intl.NumberFormat(locale).format('1.1');
let delimiter = example.charAt(1);
let cleanPattern = new RegExp(`[^-+0-9${delimiter}]`, 'g');

export default function intlParseFloat(value) {
    let cleaned = value.replace(cleanPattern, '');
    let normalized = cleaned.replace(delimiter, '.');

    return parseFloat(normalized);
};
