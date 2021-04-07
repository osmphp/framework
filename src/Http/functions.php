<?php

declare(strict_types=1);

namespace Osm {

    use Symfony\Component\HttpFoundation\Response;

    function json_response(mixed $value): Response {
        return new Response(json_encode(dehydrate($value)), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    function view_response(string $template, array $data = [],
        array $mergeData = []) : Response
    {
        return new Response((string)view($template, $data, $mergeData));
    }

    function plain_response(string $content) : Response
    {
        return new Response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}