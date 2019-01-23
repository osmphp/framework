<?php

namespace Manadev\Framework\Testing\Browsers;

use Manadev\Core\Object_;
use Manadev\Framework\Testing\Exceptions\BrowserError;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property string $name $required @part
 */
abstract class Browser extends Object_
{
    public function boot() {
        return $this;
    }

    public function terminate() {
        return $this;
    }

    /**
     * @param string $request
     * @return Document
     */
    public function html($request) {
        // process the request in separate app instance
        $response = $this->response($request);

        if (!$response->isOk()) {
            $response_ = explode("\r\n", (string)$response);
            throw new BrowserError($response_[0]);
        }

        return $this->createDocument($response);
    }

    /**
     * @param string $request
     * @return Response
     */
    abstract public function response($request);

    abstract protected function createDocument($response);
}