<?php

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Profiler;
use Osm\Core\Properties;
use Osm\Framework\Areas\Area;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Http\Errors\Error;
use Osm\Framework\Http\Errors\Errors;
use Osm\Framework\Http\Exceptions\HttpError;
use Osm\Framework\Http\Traits\AreaTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Advices $advices @required
 * @property Responses $responses @required
 * @property Controller $controller @required
 * @property Errors|Error[] $errors @required
 */
class Module extends BaseModule
{
    public $short_name = 'http';

    public $hard_dependencies = [
        'Osm_Framework_Areas',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        Area::class => AreaTrait::class,
    ];

    public static function detectEnv() {
        global $_GET;

        return $_GET['_env'] ?? null;
    }

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'advices': return $osm_app->cache->remember('http_advices', function($data) {
                global $osm_app; /* @var App $osm_app */

                return Advices::new(array_merge($data, [
                    'config' => $osm_app->config('http_advices'),
                ]));
            });
            case 'responses': return $osm_app[Responses::class];
            case 'controller': return $osm_app->controller;
            case 'errors': return $osm_app->cache->remember('http_errors', function($data) {
                return Errors::new($data);
            });
        }
        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'lifecycle');
        try {
            $response = $this->advices->around(function() {
                global $osm_app; /* @var App $osm_app */

                return $osm_app->area_->advices_->around(function() {
                    $method = $this->controller->method;
                    $returns = $this->controller->returns;

                    return $this->responses->response($returns, $this->controller->$method());
                });
            });
        }
        catch (\Throwable $e) {
            $response = $this->exception($e);
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }

        if ($e = $osm_app->pending_exception) {
            $osm_app->pending_exception = null;
            $response = $this->exception($e);
        }

        $osm_app->response = $response;

        if (!$osm_app->catch_output) {
            $response->send();
        }
    }

    public function exception(\Throwable $e) {
        if ($e instanceof HttpError) {
            return $this->error(clone $this->errors[$e->error], $e);
        }

        return $this->error(clone $this->errors['general'], $e);
    }

    protected function error(Error $error, \Throwable $e) {
        global $osm_app; /* @var App $osm_app */

        $error->e = $e;

        if ($error->response) {
            return $error->response;
        }

        /* @var Response $response */
        $response = $osm_app->createRaw(Response::class, $error->content, $error->status, [
            'Content-Type' => $error->content_type,
            'Status-Text' => $error->status_text,
        ]);

        return $response->setStatusCode($error->status, $error->status_text);
    }
}