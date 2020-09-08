export default function formatString(text, parameters = {}) {
    Object.keys(parameters)
        .sort((a, b) => b.length - a.length)
        .forEach((key) => {
            text = text.replace(new RegExp('\\:' + key, 'g'),
                parameters[key]);
        });

    return text;
}