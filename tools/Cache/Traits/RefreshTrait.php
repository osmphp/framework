<?php

namespace Osm\Tools\Cache\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Cache\Commands\Refresh;
use Osm\Framework\Console\Attributes\Option;
use Osm\Runtime\Apps;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @property ?string $app #[Option]
 */
#[UseIn(Refresh::class)]
trait RefreshTrait
{
    protected function around_run(callable $proceed): void {
        global $osm_app; /* @var App $osm_app */

        if (!$this->app || $this->app === $osm_app->name) {
            // by default, refresh the tools application
            $proceed();
            return;
        }

        Apps::run(Apps::create($this->app), function(App $app) {
            if ($app->cache) {
                $app->console->setCatchExceptions(false);
                $app->console->setAutoExit(false);

                $app->console->run(
                    new StringInput('refresh'),
                    new BufferedOutput()
                );

            }
        });

    }
}