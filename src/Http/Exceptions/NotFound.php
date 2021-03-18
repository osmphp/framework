<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Osm\__;

class NotFound extends Http
{
    public function __construct($message = "", $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message ?: __('Page not found'),
            $code, $previous);
    }

    public function response(): Response {
        return new Response($this->getMessage(), 404, [
            'Content-Type' => 'text/plain',
        ]);
    }
}