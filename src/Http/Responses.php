<?php

namespace Osm\Framework\Http;

use Osm\Core\Object_;
use Symfony\Component\HttpFoundation\Response;
use function Osm\view;
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

    public function view(string $template, array $data = [],
        array $mergeData = [], int $status = 200): Response
    {
        return new Response((string)view($template, $data, $mergeData), $status);
    }

    public function json(mixed $value): Response {
        return new Response(json_encode(dehydrate($value)), 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}