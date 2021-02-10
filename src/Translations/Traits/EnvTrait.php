<?php

declare(strict_types=1);

namespace Osm\Framework\Translations\Traits;

/**
 * @property string $APP_LOCALE
 */
trait EnvTrait
{
    /** @noinspection PhpUnused */
    protected function get_APP_LOCALE(): string {
        return 'en_US';
    }
}