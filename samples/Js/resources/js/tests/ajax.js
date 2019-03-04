import tests from 'Manadev_Samples_Js/vars/tests';
import ajax from 'Manadev_Framework_Js/ajax';

tests['/tests/api/ajax'] = function() {
    describe('ajax()', function () {
        it('normal response should be handled in .then()', function (done) {
            ajax('POST /tests/api/ajax', {payload: {}})
                .then(payload => {
                    assert.equal(JSON.parse(payload).sample, 'response');
                    done();
                });
        });
    });
};