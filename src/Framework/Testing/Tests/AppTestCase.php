<?php

namespace Manadev\Framework\Testing\Tests;

use Manadev\Framework\Migrations\Migrator;
use Manadev\Framework\Processes\Process;
use Manadev\Framework\Testing\Browser\Browser;
use Manadev\Framework\Testing\Exceptions\UndefinedBrowser;

/**
 * @property array $browsers
 */
abstract class AppTestCase extends UnitTestCase
{
    public $suite = 'app_tests';

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
                throw new UndefinedBrowser(m_("Browser ':browser' is not defined in config/test_browsers.php files",
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