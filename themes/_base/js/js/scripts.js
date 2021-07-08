import App from "./App";

requestAnimationFrame(() => {
    app.bind(document.body);
});

const app = new App();
const register = app.register.bind(app);

window.osm_app = app;

export {
    app,
    register
};