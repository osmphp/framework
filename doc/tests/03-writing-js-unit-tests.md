# Writings JS Unit Tests #

JS unit tests are written using [Mocha](https://mochajs.org/) and [Chai](https://www.chaijs.com/) libraries and grouped into several [test pages](#).

You can see list of all [test pages](#) on `base_url/tests/?_env=testing` page.

Your module can add more [test pages](#) and add JS unit tests to them in one of [sample modules](#) of your [package](#).

## Adding New Unit Test Page ##

Define new test page list item in `config/js_tests.php` of your [sample module](#). Example:

    <?php
    
    return [
        'unit' => [
            'title' => m_("Unit Tests"),
            'children' => [
                'framework' => ['title' => m_("Framework"), 'route' => 'GET /tests/unit/framework'],
            ],
        ],
    ];

Define mentioned route in [area](#) route file `config/web/routes.php`:

    <?php

    use Osm\Samples\Js\Controllers\Web;

    return [
        'GET /tests/unit/framework' => ['class' => Web::class, 'method' => 'unitTestPage', 'public' => true],
    ];

Add controller method which renders JS test runner. Optionally, render some HTML to runs JS tests on:

    <?php

    namespace Osm\Samples\Js\Controllers;

    use Osm\Framework\Http\Controller;

    class Web extends Controller
    {
        public function unitTestPage() {
            return m_layout('test');
        }
    }

## Writing Unit Test Files ##

Create unit test files, one per JS unit test page, in one of [sample modules](#), in `js/tests.js` in [module resource directory](#). As test suite grows, you may refactor it into several smaller files, in this case, put such files into `js/tests` subdirectory in [module resource directory](#).

In test file, import functions, variables and classes being tested. Also add the following import:

    import tests from 'Osm_Samples_Js/vars/tests';

Then add tests to JS unit test page using the following syntax:

    tests[url_path] = function() {
        // add all tests here
    };

Inside callback use `describe()/it()` function calls to structure unit tests and `assert.*` methods for checkng results. Check [Mocha](https://mochajs.org/) and [Chai](https://www.chaijs.com/) documentation for more details.

Example:

    import tests from 'Osm_Samples_Js/vars/tests';
    import ajax from 'Osm_Framework_Js/ajax';

    tests['/tests/unit/framework'] = function() {
        describe('ajax()', function () {
            it('normal response should be handled in .then()', function (done) {
                ajax('POST /tests/framework/ajax', {payload: {}})
                    .then(payload => {
                        assert.equal(JSON.parse(payload).sample, 'response');
                        done();
                    });
            });
        });
    };

## Adding Unit Test Files To JS Code Base ##

Import unit tests in `js/tests/index.js` in [module resource directory](#):

    import './tests';
