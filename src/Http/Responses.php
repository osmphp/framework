<?php

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Object_;
use Symfony\Component\HttpFoundation\Response;
use function Osm\__;
use function Osm\template;
use function Osm\dehydrate;

class Responses extends Object_
{
    public function plain(string $content, int $status = 200): Response {
        return new Response($content, $status, [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function notFound(string $message): Response {
        return $this->plain($message, 404);
    }

    public function forbidden(string $message): Response {
        return $this->plain($message, 403);
    }

    public function view(string $template, array $data = [],
        array $mergeData = [], int $status = 200): Response
    {
        return new Response((string)template($template, $data, $mergeData), $status);
    }

    public function json(mixed $value, int $status = 200): Response {
        return new Response(json_encode($value), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function exception(\Throwable $e): Response {
        global $osm_app; /* @var App $osm_app */

        $content = '';
        for (; $e; $e = $e->getPrevious()) {
            $content = "{$e->getMessage()}\n\n{$e->getTraceAsString()}" .
                "\n\n{$content}";
        }

        if (isset($_ENV['PRODUCTION'])) {
            $this->log($content);
            return $this->renderException();
        }

        return $this->renderException($content);
    }

    public function maintenance(): Response {
        return $this->plain(__("On maintenance"), 503);
    }

    protected function renderException(?string $content = null): Response {
        return $this->plain($content ?? __("Error"), 500);
    }

    protected function log(string $content): void {
        global $osm_app; /* @var App $osm_app */

        $osm_app->logs->http->error(
            "{$osm_app->http->request->getMethod()} " .
            "{$osm_app->http->path}: {$content}");
    }
}