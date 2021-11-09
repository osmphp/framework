import App from "./App";
import Elevation from "./Elevation";
import Capturing from "./Capturing";

requestAnimationFrame(() => {
    app.bind(document.body);
});

const app = new App();
const register = app.register.bind(app);
const controller = app.controller.bind(app);

const elevation = new Elevation();
const front = elevation.front.bind(elevation);
const back = elevation.back.bind(elevation);

const capturing = new Capturing();
const capture = capturing.capture.bind(capturing);
const release = capturing.release.bind(capturing);

window.osm_app = app;

export {
    app,
    register,
    controller,
    front,
    back,
    capture,
    release,
};