export default function isFixed(element) {
    let style = window.getComputedStyle(element);

    return style.position == 'fixed';
};
