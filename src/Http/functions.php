<?php

declare(strict_types=1);

namespace Osm {

    use Osm\Core\App;
    use Symfony\Component\HttpFoundation\Response;

    function json_response(mixed $value): Response {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->json($value);
    }

    function view_response(string $template, array $data = [],
        array $mergeData = []) : Response
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->view($template, $data, $mergeData);
    }

    function plain_response(string $content) : Response
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->plain($content);
    }

    function exception_response(\Throwable $e): Response {
        global $osm_app; /* @var App $osm_app */

        $content = '';
        for (; $e; $e = $e->getPrevious()) {
            $content = "{$e->getMessage()}\n\n{$e->getTraceAsString()}" .
                "\n\n{$content}";
        }

        if (isset($_ENV['PRODUCTION'])) {
            $osm_app->logs->http->error(
                "{$osm_app->http->request->getMethod()} " .
                "{$osm_app->http->path}: {$content}");

            return new Response(__("Error"), 500, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return new Response($content, 500, [
            'Content-Type' => 'text/plain',
        ]);
    }

    function url_decode(string $url): string {
        return rawurldecode(str_replace('+', '%20', $url));
    }

    function url_encode(string $url): string {
        return str_replace('%20', '+', rawurlencode($url));
    }
}