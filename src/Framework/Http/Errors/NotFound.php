<?php

namespace Manadev\Framework\Http\Errors;

use Symfony\Component\HttpFoundation\Response;

class NotFound extends Error
{
    public function default($property) {
        switch ($property) {
            case 'status': return Response::HTTP_NOT_FOUND;
        }
        return parent::default($property);
    }
}