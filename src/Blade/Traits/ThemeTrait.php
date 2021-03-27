<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Traits;

use Illuminate\View\Factory;
use Osm\Framework\Blade\Provider;

/**
 * @property Factory $views
 */
trait ThemeTrait
{
    /** @noinspection PhpUnused */
    protected function get_views(): Factory {
        return Provider::new(['theme' => $this])->factory;
    }
}