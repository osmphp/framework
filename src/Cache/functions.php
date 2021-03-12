<?php

//
namespace Osm {

    use Symfony\Component\Cache\Adapter\TagAwareAdapter;
    use Symfony\Contracts\Cache\ItemInterface;

    function resolve_placeholders(mixed $value, object $object): mixed {
        if (!is_string($value)) {
            return $value;
        }

        if (preg_match('/^{(?<property>[^}]+)}$/', $value, $match)) {
            return $object->{$match['property']};
        }

        return preg_replace_callback('/{(?<property>[^}]+)}/',
            fn($match) => $object->{$match['property']},
            $value);
    }
}