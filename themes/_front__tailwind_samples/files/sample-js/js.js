let should = chai.should();

describe('JS controllers', function() {
    it('are instantiated from the markup by osm_app.bind() call', function() {
        // GIVEN a markup with a JS controller
        const containerElement = document.getElementById('container');
        containerElement.innerHTML = `
            <div id="test" data-js-test='{"param": "value"}'>
            </div>
        `;

        // WHEN you instantiate and bind all JS controllers in the container
        osm_app.bind(containerElement);

        // THEN instantiated controller options are assigned as defined
        // in the markup. There are different ways to access the controller
        // object
        let test = osm_app.controller(document.getElementById('test'),
            'test');
        should.exist(test);

        test = osm_app.controller('#test', 'test');
        should.exist(test);

        test = osm_app.controller('#test:test');
        should.exist(test);

        should.exist(test.options.param);
        test.options.param.should.equal('value');
    });

    afterEach(function() {
        document.getElementById('container').innerHTML = '';
    });
});
