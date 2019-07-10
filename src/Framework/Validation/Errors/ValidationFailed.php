<?php

namespace Manadev\Framework\Validation\Errors;

use Manadev\Framework\Http\Errors\Error;
use Manadev\Framework\Validation\Exceptions;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Exceptions\ValidationFailed $e @temp
 */
class ValidationFailed extends Error
{
    public function default($property) {
        switch ($property) {
            case 'status': return Response::HTTP_BAD_REQUEST;
            case 'content_type': return 'application/json';
            case 'content': return json_encode([
                'error' => $this->name,
                'messages' => $this->e->errors,
            ], JSON_PRETTY_PRINT);
        }
        return parent::default($property);
    }
}