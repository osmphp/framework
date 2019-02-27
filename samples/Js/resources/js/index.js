import './tests/ajax';
import tests from "./vars/tests";

mocha.setup('bdd');
requestAnimationFrame(() => {
    tests[location.pathname]();
    window.assert = chai.assert;
    mocha.run();
});
