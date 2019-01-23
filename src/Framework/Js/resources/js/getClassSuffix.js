export default function getClassSuffix(element, prefix) {
    let classList = element.classList || el.className.split(/\s+/);

    let result = null;

    classList.forEach(className => {
        if (className.indexOf(prefix) === 0) {
            result = className.substr(prefix.length);
        }
    });

    return result;
};
