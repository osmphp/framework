<?php

namespace Manadev\Framework\Http\Errors;

use Symfony\Component\HttpFoundation\Response;

class ExpectedError extends Error
{
    public function default($property) {
        switch ($property) {
            case 'status': return Response::HTTP_INTERNAL_SERVER_ERROR;
            case 'content_type': return 'application/json';
            case 'content': return json_encode([
                'error' => $this->name,
            ], JSON_PRETTY_PRINT);
        }
        return parent::default($property);
    }
}