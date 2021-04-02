<?php

declare(strict_types=1);

namespace Osm {

    use Symfony\Component\HttpFoundation\Response;

    function json_response(mixed $value): Response {
        return new Response(json_encode(dehydrate($value)));
    }

    function view_response(string $template, array $data = [],
        array $mergeData = []) : Response
    {
        return new Response((string)view($template, $data, $mergeData));
    }
}