<?php

namespace Osm\Framework\Testing\Tests;

use Osm\Framework\Migrations\Migrator;
use Osm\Framework\Processes\Process;
use Osm\Framework\Testing\Browser\Browser;
use Osm\Framework\Testing\Exceptions\UndefinedBrowser;

/**
 * @property array $browsers
 */
abstract class AppTestCase extends UnitTestCase
{
    public $suite = 'app';

    protected static $areAppTestsSetUp = false;

    protected function setUp() {
        parent::setUp();

        if (static::$areAppTestsSetUp) {
            return;
        }

        if (!env('NO_MIGRATE')) {
            echo "php run migrate --fresh\n";
            Migrator::new(['fresh' => true])->migrate();
        }

        if (!env('NO_WEBPACK')) {
            echo "npm run testing-webpack\n";
            Process::runInConsole('npm run testing-webpack');
        }

        static::$areAppTestsSetUp = true;
    }

    public function __get($property) {
        switch ($property) {
            case 'browsers': return $this->module->browsers;
        }

        return parent::__get($property);
    }

    /**
     * @param string|string[]|array $browsers
     * @param callable $callback
     */
    protected function browse($browsers, callable $callback) {
        /* @var Browser[] $browsers_ */
        $browsers_ = [];

        if (is_string($browsers)) {
            $browsers = [$browsers];
        }

        foreach ($browsers as $browser) {
            if (is_string($browser)) {
                $browser = ['name' => $browser];
            }

            if (!isset($this->browsers[$browser['name']])) {
                throw new UndefinedBrowser(osm_t("Browser ':browser' is not defined in config/test_browsers.php files",
                    ['browser' => $browser['name'] ?? '']));
            }

            $browsers_[] = Browser::new(array_merge($this->browsers[$browser['name']], $browser))->boot();
        }

        $callback(...$browsers_);

        foreach ($browsers_ as $browser_) {
            $browser_->terminate();
        }
    }
}