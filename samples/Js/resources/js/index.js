import './tests';
import tests from "./vars/tests";

if (typeof mocha !== 'undefined') {
    mocha.setup('bdd');
    requestAnimationFrame(() => {
        tests[location.pathname]();
        window.assert = chai.assert;
        mocha.run();
    });
}
