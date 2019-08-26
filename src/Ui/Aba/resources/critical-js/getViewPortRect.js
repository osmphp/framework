import addClass from 'Osm_Framework_Js/addClass';
let viewportElements = {};

export default function getViewPortRect(class_ = null) { //
    let rect = {
        left: 0,
        top: 0,
        right: document.documentElement.clientWidth,
        bottom: document.documentElement.clientHeight
    };

    if (!class_) {
        rect.width = rect.right - rect.left;
        rect.height = rect.bottom - rect.top;
        return rect;
    }

    if (!viewportElements[class_]) {
        let element = document.createElement('div');
        addClass(element, class_ + '__viewport');
        document.body.appendChild(element);
        viewportElements[class_] = element;
    }

    let style = getComputedStyle(viewportElements[class_]);
    rect.left += parseFloat(style.paddingLeft);
    rect.top += parseFloat(style.paddingTop);
    rect.right -= parseFloat(style.paddingRight);
    rect.bottom -= parseFloat(style.paddingBottom);
    rect.width = rect.right - rect.left;
    rect.height = rect.bottom - rect.top;

    return rect;
};

