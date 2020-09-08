export default function apiElement(api) {
    if (!api) {
        return api;
    }

    if (!api.element) {
        throw "apiElement() function can only be used on API bound to HTML element";
    }

    return api.element;
}
