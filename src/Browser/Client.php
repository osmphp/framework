<?php

declare(strict_types=1);

namespace Osm\Framework\Browser;

use Osm\Core\App;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Client extends AbstractBrowser
{
    /**
     * @param Request $request
     * @return Response
     * @noinspection PhpMissingParamTypeInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function doRequest($request) {
        global $osm_app; /* @var App $osm_app */

        return $this->fromSymfonyResponse($osm_app->handleHttpRequest([
            'request' => $this->toSymfonyRequest($request),
        ]));
    }

    protected function toSymfonyRequest(Request $request): SymfonyRequest {
        return SymfonyRequest::create($request->getUri(), $request->getMethod(),
            $request->getParameters(), $request->getCookies(),
            $request->getFiles(), $request->getServer(), $request->getContent());
    }

    protected function fromSymfonyResponse(SymfonyResponse $response): Response {
        return new Response($response->getContent(), $response->getStatusCode(),
            $response->headers->all());
    }
}