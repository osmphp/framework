export default function indexOf(parentElement, childElement) {
    for (let i = 0; i < parentElement.children.length; i++) {
        if (parentElement.children[i] === childElement) {
            return i;
        }
    }

    return -1;
};
