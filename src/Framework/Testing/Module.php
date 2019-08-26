<?php

namespace Osm\Framework\Testing;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property TestSuites|TestSuite[] $suites
 * @property array $browsers
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Console',
        'Osm_Framework_Localization',
    ];

    public $short_name = 'testing';

    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'suites': return TestSuites::new();
            case 'browsers': return $osm_app->config('test_browsers');
        }
        return parent::default($property);
    }
}