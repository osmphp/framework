import './tests';
import tests from "./vars/tests";
import config from "Osm_Framework_Js/vars/config";

if (typeof mocha !== 'undefined') {
    mocha.setup('bdd');
    requestAnimationFrame(() => {
        let test = location.href.substr(config.base_url.length);
        if (location.search) {
            test = test.substr(0, test.length - location.search.length);
        }
        tests[test]();
        window.assert = chai.assert;
        mocha.run();
    });
}
