import tests from 'Osm_Samples_Js/vars/tests';
import ajax from 'Osm_Framework_Js/ajax';

tests['/tests/unit/framework'] = function() {
    describe('vendor/dubysa/framework/samples/Js/resources/js/tests.js', function() {
        describe('ajax()', function () {
            it('normal response should be handled in .then()', function (done) {
                ajax('POST /tests/framework/ajax', {payload: {}})
                    .then(payload => {
                        assert.equal(JSON.parse(payload).sample, 'response');
                        done();
                    });
            });
        });
    });
};