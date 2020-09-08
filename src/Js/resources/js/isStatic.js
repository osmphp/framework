export default function isStatic(element) {
    let style = window.getComputedStyle(element);

    return style.position == 'static';
};
