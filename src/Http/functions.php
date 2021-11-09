<?php

declare(strict_types=1);

namespace Osm {

    use Osm\Core\App;
    use Symfony\Component\HttpFoundation\Response;

    function json_response(mixed $value, int $status = 200): Response {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->json($value, $status);
    }

    function view_response(string $template, array $data = [],
        array $mergeData = [], int $status = 200) : Response
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->view($template, $data, $mergeData,
            $status);
    }

    function plain_response(string $content, int $status = 200) : Response
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->plain($content, $status);
    }

    function exception_response(\Throwable $e): Response {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses->exception($e);
    }

    function url_decode(string $url): string {
        return rawurldecode(str_replace('+', '%20', $url));
    }

    function url_encode(string $url): string {
        return str_replace('%20', '+', rawurlencode($url));
    }
}