<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\Object_;
use Osm\Framework\Cache\Attributes\Cached;
use Osm\Framework\Cache\Cache;
use Osm\Framework\Samples\App;
use Symfony\Contracts\Cache\ItemInterface;
use function Osm\resolve_placeholders;

trait ObjectTrait
{
    /** @noinspection PhpUnused */
    protected function around_default(callable $proceed, string $property): mixed {
        global $osm_app; /* @var App $osm_app */

        /* @var Object_ $this */

        // if property is not cached, compute it as usual
        if (!isset($this->__class->properties[$property]->attributes[Cached::class])) {
            return $proceed($property);
        }

        /* @var Cached $attribute */
        $attribute = $this->__class->properties[$property]->attributes[Cached::class];

        /* @var Cache $cache */
        $cache = $osm_app->{$attribute->cache_name};

        // replace {property} placeholders with actual property values
        $key = resolve_placeholders($attribute->key, $this);

        return $cache->get($key, function (ItemInterface $item)
            use ($proceed, $property, $attribute)
        {
            // replace {property} placeholders with actual property values
            if (!empty($attribute->tags)) {
                $item->tag(array_map(fn($tag) => resolve_placeholders($tag, $this),
                    $attribute->tags));
            }

            if ($attribute->expires_after !== null) {
                $item->expiresAfter(resolve_placeholders($attribute->expires_after,
                    $this));
            }

            // compute property value
            $this->$property = $proceed($property);

            if ($attribute->callback) {
                //
                $this->{$attribute->callback}();
            }

            return $this->$property;
        });
    }
}