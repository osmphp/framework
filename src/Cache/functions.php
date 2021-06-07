<?php

//
namespace Osm {

    use Osm\Core\App;
    use Osm\Core\Object_;

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

    function delete_dir($path) {
        if (!is_dir($path)) {
            return;
        }

        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isDir()) {
                delete_dir("{$path}/{$fileInfo->getFilename()}");
            }
            else {
                unlink("{$path}/{$fileInfo->getFilename()}");
            }
        }

        rmdir($path);
    }

    function create(string $className, ?string $descendantName = null,
        array $data = []): Object_
    {
        $className = subclass_name($className, $descendantName);
        $new = "{$className}::new";

        return $new($data);
    }

    function subclass_name(string $className, ?string $descendantName = null) {
        global $osm_app; /* @var App $osm_app */

        if (!$descendantName) {
            return $className;
        }

        $descendants = $osm_app->descendants->byName($className);
        if (isset($descendants[$descendantName])) {
            return $descendants[$descendantName];
        }

        return $className;
    }
}