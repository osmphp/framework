<?php

use Osm\Core\App;
use Osm\Core\Merger;
use Osm\Core\Object_;
use Osm\Core\Promise;
use Osm\Framework\Layers\Layout;
use Osm\Framework\Views\View;

function osm_make_dir($dir, $mode = null) {
    global $osm_app; /* @var App $osm_app */

    if (!is_dir($dir)) {
        mkdir($dir, $mode ?: $osm_app->writable_directory_permissions, true);
    }
    return $dir;
}

function osm_make_dir_for($filename, $mode = null) {
    global $osm_app; /* @var App $osm_app */

    osm_make_dir(dirname($filename), $mode ?: $osm_app->writable_directory_permissions);
    return $filename;
}

function osm_merge($target, ...$sources) {
    return Merger::merge($target, ...$sources);
}

function osm_non_nulls($data) {
    return array_filter((array)$data, function($value) {
        return $value !== null;
    });
}
function osm_path($path) {
    return new Promise(null, 'path', [$path]);
}

function osm_t($text, $parameters = []) {
    return new Promise('localization', 'translate', [$text, $parameters]);
}

function osm_url($route, $parsedQuery = [], $data = []) {
    return new Promise('url_generator', 'routeUrl', [$route, $parsedQuery, $data]);
}

function osm_current_url($parsedQuery = [], $data = []) {
    global $osm_app; /* @var App $osm_app */

    return osm_url($osm_app->request->method_and_route, $parsedQuery, $data);
}

function osm_asset($path) {
    return new Promise('url_generator', 'assetUrl', [$path]);
}

function osm_env($name, $default = null) {
    return new Promise(null, 'getEnv', [$name, $default]);
}

function osm_layout(...$layers) {
    return Layout::new()->load(...$layers);
}

function osm_view($content) {
    if (is_string($content)) {
        $content = View::new(['template' => $content]);
    }
    return (string)$content;
}

function osm_core_log($message, $filename = 'core.log') {
    $dir = __DIR__;
    $filename = realpath($dir . '/../../../..') . "/temp/log/{$filename}";
    osm_make_dir_for($filename, 0777);
    file_put_contents($filename, $message . "\n", FILE_APPEND | LOCK_EX);

    // chmod may fail if log was initially created by www user and then appended to by console user or vice versa.
    // if so, file was already chmod'ed by user which created the file, so we just ignore it
    @chmod($filename, 0666);
}

function osm_core_log_stack_trace($filename = 'core.log') {
    try {
        throw new \Exception();
    }
    catch (\Exception $e) {
        osm_core_log($e->getTraceAsString(), $filename);
    }
}

if (!function_exists('is_iterable' )) {
    function is_iterable($var) {
        return is_array($var) || (is_object($var) && ($var instanceof \Traversable));
    }

}

/**
 * @param mixed $var
 * @return array
 */
function osm_array($var) {
    if (is_iterable($var)) {
        $result = [];
        foreach ($var as $key => $value) {
            $result[$key] = osm_array($value);
        }
        return $result;
    }
    if ($var instanceof Object_) {
        return $var->toArray();
    }
    return is_object($var) ? (array)$var: $var;
}

/**
 * @param mixed $var
 * @return object|array
 */
function osm_object($var) {
    if (is_iterable($var)) {
        $result = [];
        foreach ($var as $key => $value) {
            $result[$key] = osm_object($value);
        }
        return $result;
    }
    if ($var instanceof Object_) {
        return $var->toObject();
    }
    if (is_object($var)) {
        foreach ($var as &$value) {
            $value = osm_object($value);
        }
    }
    return $var;
}
