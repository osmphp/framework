<?php

namespace Osm\Framework\Testing\RawBrowser;

use Osm\Core\App;
use Osm\Framework\Http\Request;
use Osm\Framework\Testing\Browser\Browser as BaseBrowser;
use Osm\Framework\Testing\Exceptions\BrowserError;
use Osm\Framework\Testing\Exceptions\InvalidRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;


class Browser extends BaseBrowser
{
    /**
     * @var int @part @required
     */
    public $max_redirects = 3;

    /**
     * @param string $request
     * @return Response
     */
    public function response($request) {
        $response = $this->run($request);

        for ($i = 0; $i < $this->max_redirects; $i++) {
            if (!$response->isRedirect()) {
                return $response;
            }

            $url = $response->headers->get('Location');
            if (strpos($url, env('APP_URL') === 0)) {
                $url = substr($url, strlen(env('APP_URL')));
            }

            $response = $this->run("GET {$url}");
        }

        throw new BrowserError(osm_t("Too many redirects"));
    }

    /**
     * @param string $request
     * @return Response
     */
    protected function run($request) {

        // add headers from current browsing session
        $request = $this->addBrowserState($request);

        // convert request string to symfony request
        $request_ = $this->parseRequest($request);

        $dir = __DIR__;

        $app = App::runApp('http', [
            'base_path' => realpath($dir . '/../../../../../../../'),
            'env' => 'testing',
            'request' => Request::new(['symfony_request' => $request_]),
            'catch_output' => true,
        ]);

        $this->updateBrowserState($app->response);

        return $app->response;
    }


    /**
     * @param string $request
     * @return $string
     */
    protected function addBrowserState($request) {
        return $request;
    }

    protected function updateBrowserState($result) {
    }

    /**
     * @param $request
     * @return SymfonyRequest
     */
    protected function parseRequest($request) {
        if (!$request) {
            throw new InvalidRequest(osm_t("Request can't be empty"));
        }

        $lines = array_map('trim', explode("\n", $request));
        $urlLineIndex = null;
        $urlMatch = null;

        foreach ($lines as $i => $line) {
            if (preg_match('/^(?<method>get|post|delete)\s+(?<url>.+)$/i', $line, $urlMatch)) {
                $urlLineIndex = $i;
                break;
            }
        }

        if ($urlLineIndex === null) {
            throw new InvalidRequest(osm_t("URL line starting with GET, POST or DELETE not found"));
        }

        $result = new SymfonyRequest();
        $result->server->set('REQUEST_URI', $urlMatch['url']);

        return $result;
    }

    /**
     * @param Response $response
     * @return Document
     */
    protected function createDocument($response) {
        return Document::new(['html' => $response->getContent()], null, $this);
    }
}