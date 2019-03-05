<?php

namespace Manadev\Framework\Http\Errors;

use Symfony\Component\HttpFoundation\Response;

class ExpectedError extends Error
{
    public function __get($property) {
        switch ($property) {
            case 'status': return Response::HTTP_INTERNAL_SERVER_ERROR;
            case 'content_type': return 'application/json';
            case 'content': return json_encode([
                'error' => $this->name,
            ], JSON_PRETTY_PRINT);
        }
        return parent::__get($property);
    }
}