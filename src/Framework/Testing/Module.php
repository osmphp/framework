<?php

namespace Manadev\Framework\Testing;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;

/**
 * @property TestSuites|TestSuite[] $suites
 * @property array $browsers
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Framework_Console',
        'Manadev_Framework_Localization',
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