let should = chai.should();

describe('App', function() {
    describe('bind()', function () {
        it('instantiates controllers from the markup', function() {
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
            const testElement = document.getElementById('test');
            let test = osm_app.controller(testElement, 'test');
            should.exist(test);

            test = osm_app.controller('#test', 'test');
            should.exist(test);

            test = osm_app.controller('#test:test');
            should.exist(test);

            test.options.param.should.equal('value');
        });

        it('attaches controller event handlers', function() {
            // GIVEN a markup with a JS controller
            const containerElement = document.getElementById('container');
            containerElement.innerHTML = `
                <div id="test" data-js-test='{"param": "value"}'>
                </div>
            `;

            // WHEN you instantiate and bind all JS controllers in the container
            osm_app.bind(containerElement);

            // AND trigger an event
            const testElement = document.getElementById('test');
            testElement.dispatchEvent(new MouseEvent('click'));

            // THEN instantiated controller event handler is triggered
            osm_app.controller('#test:test').clicked.should.equal(true);
        });

        afterEach(function() {
            document.getElementById('container').innerHTML = '';
        });
    });
});
