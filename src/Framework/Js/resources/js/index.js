import Broadcasts from "./Broadcasts";
import macaw from "./vars/macaw";
import WidthClass from "./WidthClass";

macaw.attachControllerToElement(Broadcasts, document.body);
macaw.controller('*[data-width-class]', WidthClass, null,
    'data-width-class');

window.requestAnimationFrame(() => {
    macaw.ready();
});

