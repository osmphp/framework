<?php

namespace Manadev\Framework\Testing\ChromeBrowser;

use Manadev\Framework\Testing\Browser\Browser as BaseBrowser;
use Symfony\Component\HttpFoundation\Response;

class Browser extends BaseBrowser
{

    /**
     * @param string $request
     * @return Response
     */
    public function response($request) {

    }

    protected function createDocument($response) {

    }
}