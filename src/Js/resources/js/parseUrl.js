export default function parseUrl(href) {
    let a = document.createElement('a');
    a.href = href;
    return a;
};