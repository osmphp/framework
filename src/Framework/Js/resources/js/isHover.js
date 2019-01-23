export default function isHover(element) {
    return (element.parentElement.querySelector(':hover') === element);
}