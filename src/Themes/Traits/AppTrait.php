<?php

declare(strict_types=1);

namespace Osm\Framework\Themes\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Themes\Theme;

/**
 * @property Theme $theme
 */
#[UseIn(App::class)]
trait AppTrait
{
    protected function get_theme(): ?Theme {
        return null;
    }
}