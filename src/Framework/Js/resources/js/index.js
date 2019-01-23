import Broadcasts from "./Broadcasts";
import macaw from "./vars/macaw";

macaw.attachControllerToElement(Broadcasts, document.body);

window.requestAnimationFrame(() => {
    macaw.ready();
});

