export default function isScrollable(element) {
    let style = window.getComputedStyle(element);

    return style.overflowX == 'scroll' || style.overflowX == 'auto' ||
        style.overflowY == 'scroll' || style.overflowY == 'auto';
};
