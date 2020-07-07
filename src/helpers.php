<?php

use Osm\Core\App;
use Osm\Core\Merger;
use Osm\Core\Object_;
use Osm\Core\Promise;
use Osm\Framework\Emails\Jobs\SendEmail;
use Osm\Framework\Emails\Views\Email;
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

function osm_parse_float($value) {
    global $osm_app; /* @var App $osm_app */

    return $osm_app->localization->float->parse($value);
}

function osm_float_to_string($value) {
    global $osm_app; /* @var App $osm_app */

    return $osm_app->localization->float->format($value);
}

function osm_url($route, $parsedQuery = []) {
    return new Promise('url', 'toRoute', [$route, $parsedQuery]);
}

function osm_area_url($area, $route, $parsedQuery = []) {
    return new Promise('url', 'toAreaRoute', [$area, $route, $parsedQuery]);
}

function osm_current_url($parsedQuery = []) {
    global $osm_app; /* @var App $osm_app */

    return osm_url($osm_app->request->method_and_route, $parsedQuery);
}

function osm_asset($path) {
    return new Promise('url', 'toAsset', [$path]);
}

function osm_env($name, $default = '') {
    return new Promise(null, 'getEnv', [$name, $default]);
}

/**
 * @param mixed ...$layers
 * @return Layout
 */
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
    $timestamp = date(DATE_ATOM);
    osm_make_dir_for($filename, 0777);
    file_put_contents($filename, "\n{$timestamp}\n{$message}\n", FILE_APPEND | LOCK_EX);

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

function osm_delete_dir($path) {
    if (!is_dir($path)) {
        return;
    }

    foreach (new \DirectoryIterator($path) as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }

        if ($fileInfo->isDir()) {
            osm_delete_dir("{$path}/{$fileInfo->getFilename()}");
        }
        else {
            unlink("{$path}/{$fileInfo->getFilename()}");
        }
    }

    rmdir($path);
}

function osm_copy_dir($target, $source) {
    if (!is_dir($source)) {
        return;
    }

    if (!is_dir($target)) {
        osm_make_dir($target);
    }

    foreach (new \DirectoryIterator($source) as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }

        if ($fileInfo->isDir()) {
            osm_copy_dir("{$target}/{$fileInfo->getFilename()}",
                "{$source}/{$fileInfo->getFilename()}");
        }
        else {
            copy("{$source}/{$fileInfo->getFilename()}",
                "{$target}/{$fileInfo->getFilename()}");
        }
    }
}

function osm_is_absolute_url($url) {
    return starts_with(strtolower($url), ['http://', 'https://']);
}

/**
 * Renders specified layers into an email and sends it. Layers should be
 * based on 'email' base layer
 *
 * Returns number of emails sent
 *
 * @param Email $email
 * @return int
 */
function osm_send_email($email) {
    global $osm_app; /* @var App $osm_app */

    $message = (new Swift_Message((string)$email->subject))
        ->setFrom($email->from)
        ->setTo($email->to)
        ->setBody($email->body, 'text/html')
        ->addPart($email->plain, 'text/plain');

    if ($osm_app->settings->use_email_queue) {
        $osm_app->queues->dispatch(SendEmail::new([
            'email' => serialize($message),
        ]));
        return 0;
    }
    else {
        /* @var \Osm\Framework\Emails\Module $module */
        $module = $osm_app->modules['Osm_Framework_Emails'];

        return $module->send($message);
    }
}