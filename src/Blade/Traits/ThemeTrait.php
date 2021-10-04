<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Traits;

use Illuminate\View\Factory;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Blade\Provider;
use Osm\Framework\Themes\Theme;

/**
 * @property Factory $views
 */
#[UseIn(Theme::class)]
trait ThemeTrait
{
    /** @noinspection PhpUnused */
    protected function get_views(): Factory {
        return Provider::new(['theme' => $this])->factory;
    }
}