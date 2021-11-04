<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Exceptions\InvalidParameter;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Http\Hints\BaseUrl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Osm\__;

/**
 * @property Request $request
 * @property Responses $responses
 * @property string $base_url
 * @property string $path
 * @property string $area_class_name
 * @property string $route_class_name
 * @property array $route_parameters
 * @property Module $module
 * @property Area $area
 * @property Route $route
 * @property BaseUrl[] $base_urls
 * @property array $query
 * @property string $content
 * @property bool $running
 * @property string $title Default page meta title
 */
class Http extends Object_
{
    public function run(): Response {
        $this->running = true;

        try {
            return $this->module->around(function() {
                $this->detectArea();

                return $this->module->around(function() {
                    $this->detectRoute();

                    return $this->route->run();
                }, $this->area_class_name);
            });
        }
        finally {
            $this->running = false;
        }
    }

    /** @noinspection PhpUnused */
    protected function get_request(): Request {
        if (!$this->running) {
            throw new NotSupported(__(
                "Only use `\$osm_app->http` object while actually handling a HTTP request."));
        }

        return Request::createFromGlobals();
    }

    protected function get_responses(): Responses {
        return Responses::new();
    }

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }

    /** @noinspection PhpUnused */
    protected function get_area(): Area {
        $new = "{$this->area_class_name}::new";

        return $new();
    }

    /** @noinspection PhpUnused */
    protected function get_route(): Route {
        $new = "{$this->route_class_name}::new";

        return $new($this->route_parameters);
    }

    protected function detectArea(): void {
        $url = "{$this->base_url}{$this->request->getPathInfo()}";
        $placeholderUrl = "{{base_url}}{$this->request->getPathInfo()}";

        foreach ($this->base_urls as $baseUrl) {
            if (str_starts_with($url, $baseUrl->base_url))
            {
                $this->path = substr($url, strlen($baseUrl->base_url));
            }
            elseif (str_starts_with($placeholderUrl, $baseUrl->base_url))
            {
                $this->path = substr($placeholderUrl, strlen($baseUrl->base_url));
            }
            else {
                continue;
            }

            foreach ($baseUrl as $property => $value) {
                if ($property == 'base_url') {
                    continue;
                }

                $this->$property = $value;
            }

            return;
        }

        throw new NotFound();
    }

    protected function detectRoute(): void {
        $routeName = "{$this->request->getMethod()} {$this->path}";
        $this->route_parameters = [];

        if ($this->route_class_name =
            $this->module->routes[$this->area_class_name][$routeName] ?? null)
        {
            if (is_array($this->route_class_name)) {
                foreach ($this->route_class_name as $className => $parameters) {
                    $this->route_parameters = $parameters;
                    $this->route_class_name = $className;
                    break;
                }
            }

            return;
        }

        foreach ($this->module->dynamic_routes[$this->area_class_name] ?? []
            as $routeClassName)
        {
            $new = "{$routeClassName}::new";
            $route = $new(); /* @var Route $route */
            if (!($this->route = $route->match())) {
                continue;
            }

            $this->route_class_name = $routeClassName;
            return;
        }

        throw new NotFound();
    }

    /** @noinspection PhpUnused */
    protected function get_base_urls(): array {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->base_urls;
    }

    /** @noinspection PhpUnused */
    protected function get_base_url(): string {
        return $this->request->getSchemeAndHttpHost() .
            $this->request->getBaseUrl();
    }

    /** @noinspection PhpUnused */
    protected function get_query(): array {
        $query = [];

        if ($queryString = $this->request->server->get('QUERY_STRING')) {
            $parameters = array_filter(explode('&', $queryString));
            foreach ($parameters as $parameter) {
                $this->parseParameter($query, $parameter);
            }
        }

        return $query;
    }

    protected function parseParameter(array &$query, string $parameterString)
        : void
    {
        if (($pos = mb_strpos($parameterString, '=')) === false) {
            $key = $this->decode($parameterString);
            $value = '';
        }
        else {
            $key = $this->decode(mb_substr($parameterString, 0, $pos));
            $value = $this->decode(mb_substr($parameterString, $pos + 1));
        }

        if (str_ends_with($key, '[]')) {
            $key = mb_substr($key, 0, mb_strlen($key) - 2);
            $isArray = true;
        }
        else {
            $isArray = false;
        }

        if ($pos !== false) {
            $this->parseValueOrArray($query, $key, $value, $isArray);
        }
        else {
            $this->parseFlag($query, $key, $isArray);
        }
    }

    public function decode(string $url): string {
        return rawurldecode(str_replace('+', '%20', $url));
    }

    protected function parseFlag(array &$query, string $key, bool $isArray)
        : void
    {
        if ($isArray) {
            throw new InvalidParameter(__(
                "Flag parameter ':name' can't be an array",
                ['name' => $key]));
        }

        if (isset($query[$key])) {
            throw new InvalidParameter(__(
                "Parameter ':name' can't have value and be a flag at the same time",
                ['name' => $key]));
        }

        $query[$key] = true;
    }

    protected function parseValueOrArray(array &$query, string $key,
        string $value, bool $isArray): void
    {
        if ($isArray) {
            $this->parseArray($query, $key, $value);
            return;
        }

        if (isset($query[$key])) {
            $this->parseArray($query, $key, $value);
            return;
        }

        $query[$key] = $value;
    }

    protected function parseArray(array &$query, string $key, string $value)
        : void
    {
        $arrayValue = $query[$key] ?? [];
        if (!is_array($arrayValue)) {
            $arrayValue = [$arrayValue];
        }

        $arrayValue[] = $value;
        $query[$key] = $arrayValue;
    }

    protected function get_content(): string {
        return $this->request->getContent();
    }
}