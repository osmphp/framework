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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'suites': return TestSuites::new();
            case 'browsers': return $m_app->config('test_browsers');
        }
        return parent::default($property);
    }
}