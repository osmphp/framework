<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class Array_ extends Cache
{
    protected function get_adapter(): TagAwareAdapter {
        $items = new ArrayAdapter();
        $tags = new ArrayAdapter();

        return new TagAwareAdapter($items, $tags);

    }
}