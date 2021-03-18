<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Exceptions;

use Throwable;
use function Osm\__;

class NotFound extends \Exception
{
    public function __construct($message = "", $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message ?: __('Page not found'),
            $code, $previous);
    }
}