<?php

namespace Manadev\Framework\Testing;

use Manadev\Core\App;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @property string $suite
 * @property \Manadev\Framework\Testing\Module $module
 */
abstract class TestCase extends BaseTestCase
{
    public static $app_instance;

    public function __get($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'module':
                return $m_app->modules['Manadev_Framework_Testing'];
        }

        return null;
    }

    protected function recreateApp() {
        $dir = __DIR__;
        return static::$app_instance = App::createApp([
            'base_path' => realpath($dir . '/../../../../../../'),
            'env' => 'testing',
        ])->boot();
    }
}