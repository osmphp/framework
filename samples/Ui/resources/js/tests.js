import tests from 'Osm_Samples_Js/vars/tests';
import snackBars from 'Osm_Ui_SnackBars/vars/snackBars';
import ajax from 'Osm_Framework_Js/ajax';
import osm_t from "Osm_Framework_Js/osm_t";

tests['/tests/unit/ui'] = function () {
    describe('vendor/osmphp/framework/samples/Ui/resources/js/tests.js', function() {
        describe('ajax() with `snackbar_message` option', function () {
            it('normal response should be handled in .then()', function (done) {
                ajax('POST /tests/framework/ajax', {payload: {}, snackbar_message: osm_t("Processing ...")})
                    .then(payload => {
                        payload = JSON.parse(payload);
                        snackBars.showMessage('Normal response received');
                        assert.equal(payload.sample, 'response');
                        done();
                    });
            });
            it('unexpected error response should be handled by the framework', function (done) {
                ajax('POST /tests/framework/not-implemented', {payload: {}, snackbar_message: osm_t("Processing ...")})
                    .then(payload => {
                        assert.isUndefined(payload); // handled response is passed as undefined value
                        done();
                    });
            });
            it('expected error response should be JSON handled in .catch()', function (done) {
                ajax('POST /tests/framework/error', {payload: {}, snackbar_message: osm_t("Processing ...")})
                    .catch(xhr => {
                        let payload = JSON.parse(xhr.responseText);
                        assert.equal(payload.error, 'expected_error');
                        snackBars.showMessage(xhr.getResponseHeader('Status-Text'));
                        done();
                    });
            });
        });
    });
};