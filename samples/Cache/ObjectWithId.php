<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Samples\Cache;

use Osm\Core\Object_;
use Osm\Framework\Cache\Attributes\Cached;

/**
 * @property int $id #[Cached('id_{name}')]
 */
class ObjectWithId extends Object_
{
    public string $name = 'test_cache_key';

    /** @noinspection PhpUnused */
    protected function get_id(): int {
        static $value = 0;

        return ++$value;
    }
}