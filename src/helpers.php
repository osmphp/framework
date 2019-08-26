<?php

use Osm\Core\App;
use Osm\Core\Classes\Classes;
use Osm\Core\Merger;
use Osm\Core\Object_;
use Osm\Core\Promise;
use Osm\Framework\Layers\Layout;
use Osm\Framework\Views\View;

function m_make_dir($dir, $mode = null) {
    global $m_app; /* @var App $m_app */

    if (!is_dir($dir)) {
        mkdir($dir, $mode ?: $m_app->writable_directory_permissions, true);
    }
    return $dir;
}

function m_make_dir_for($filename, $mode = null) {
    global $m_app; /* @var App $m_app */

    m_make_dir(dirname($filename), $mode ?: $m_app->writable_directory_permissions);
    return $filename;
}

function m_merge($target, ...$sources) {
    return Merger::merge($target, ...$sources);
}

function m_non_nulls($data) {
    return array_filter((array)$data, function($value) {
        return $value !== null;
    });
}
function m_path($path) {
    return new Promise(null, 'path', [$path]);
}

function m_($text, $parameters = []) {
    return new Promise('localization', 'translate', [$text, $parameters]);
}

function m_url($route, $parsedQuery = [], $data = []) {
    return new Promise('url_generator', 'routeUrl', [$route, $parsedQuery, $data]);
}

function m_current_url($parsedQuery = [], $data = []) {
    global $m_app; /* @var App $m_app */

    return m_url($m_app->request->method_and_route, $parsedQuery, $data);
}

function m_asset($path) {
    return new Promise('url_generator', 'assetUrl', [$path]);
}

function m_env($name, $default = null) {
    return new Promise(null, 'getEnv', [$name, $default]);
}

function m_layout(...$layers) {
    return Layout::new()->load(...$layers);
}

function m_view($content) {
    if (is_string($content)) {
        $content = View::new(['template' => $content]);
    }
    return (string)$content;
}

function m_core_log($message, $filename = 'core.log') {
    $dir = __DIR__;
    $filename = realpath($dir . '/../../../..') . "/temp/log/{$filename}";
    m_make_dir_for($filename, 0777);
    file_put_contents($filename, $message . "\n", FILE_APPEND | LOCK_EX);

    // chmod may fail if log was initially created by www user and then appended to by console user or vice versa.
    // if so, file was already chmod'ed by user which created the file, so we just ignore it
    @chmod($filename, 0666);
}

function m_core_log_stack_trace($filename = 'core.log') {
    try {
        throw new \Exception();
    }
    catch (\Exception $e) {
        m_core_log($e->getTraceAsString(), $filename);
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
function m_array($var) {
    if (is_iterable($var)) {
        $result = [];
        foreach ($var as $key => $value) {
            $result[$key] = m_array($value);
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
function m_object($var) {
    if (is_iterable($var)) {
        $result = [];
        foreach ($var as $key => $value) {
            $result[$key] = m_object($value);
        }
        return $result;
    }
    if ($var instanceof Object_) {
        return $var->toObject();
    }
    if (is_object($var)) {
        foreach ($var as &$value) {
            $value = m_object($value);
        }
    }
    return $var;
}
