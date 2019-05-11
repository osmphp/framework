<?php

namespace Manadev\Framework\Http;

use Manadev\Core\Object_;
use Manadev\Framework\Http\Exceptions\InvalidParameter;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * @property SymfonyRequest $symfony_request @required
 * @property string $asset_base @required @part
 * @property string $base @required @part
 * @property string $route @required @part
 * @property string $method @required @part
 * @property string $method_and_route @required
 * @property string $content @part
 * @property bool $ajax @part
 * @property array $query @required @part Raw unfiltered query parameters. Value type is:
 *      string - in most cases
 *      string[] - if same parameter in URL is met more than once or if parameter name ends with '[]'
 *      true - if there is parameter name but no value
 */
class Request extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'symfony_request': return SymfonyRequest::createFromGlobals();
            case 'asset_base': return $this->symfony_request->getUriForPath('');
            case 'base': return $this->symfony_request->getUriForPath('');
            case 'route': return $this->decode($this->symfony_request->getPathInfo());
            case 'method': return $this->symfony_request->getMethod();
            case 'method_and_route': return "{$this->method} {$this->route}";
            case 'content': return $this->symfony_request->getContent();
            case 'ajax': return $this->symfony_request->isXmlHttpRequest();

            case 'query': return $this->parseQuery();
        }
        return parent::default($property);
    }

    protected function parseQuery() {
        $query = [];

        if ($queryString = $this->symfony_request->server->get('QUERY_STRING')) {
            foreach (array_filter(explode('&', $queryString)) as $parameterString) {
                $this->parseParameter($query, $parameterString);
            }
        }

        return $query;
    }

    /**
     * @param array $query
     * @param string $parameterString
     * @return null
     */
    protected function parseParameter(&$query, $parameterString) {
        if (($pos = mb_strpos($parameterString, '=')) === false) {
            $key = $this->decode($parameterString);
            $value = '';
        }
        else {
            $key = $this->decode(mb_substr($parameterString, 0, $pos));
            $value = $this->decode(mb_substr($parameterString, $pos + 1));
        }

        if (ends_with($key, '[]')) {
            $key = mb_substr($key, 0, mb_strlen($key) - 2);
            $isArray = true;
        }
        else {
            $isArray = false;
        }

        return $pos !== false
            ? $this->parseValueOrArray($query, $key, $value, $isArray)
            : $this->parseFlag($query, $key, $isArray);
    }

    public function decode($url) {
        return rawurldecode(str_replace('+', '%20', $url));
    }

    /**
     * @param array $query
     * @param string $key
     * @param bool $isArray
     * @return null
     */
    protected function parseFlag(&$query, $key, $isArray) {
        if ($isArray) {
            throw new InvalidParameter(m_("Flag parameter ':name' can't be an array", [
                'name' => $key,
            ]));
        }

        if (isset($query[$key])) {
            throw new InvalidParameter(m_("Parameter ':name' can't have value and be a flag at the same time", [
                'name' => $key,
            ]));
        }

        $query[$key] = true;
        return null;
    }

    /**
     * @param array $query
     * @param string $key
     * @param string $value
     * @param bool $isArray
     * @return null
     */
    protected function parseValueOrArray(&$query, $key, $value, $isArray) {
        if ($isArray) {
            return $this->parseArray($query, $key, $value);
        }

        if (isset($query[$key])) {
            return $this->parseArray($query, $key, $value);
        }

        return $this->parseValue($query, $key, $value);
    }

    /**
     * @param array $query
     * @param string $key
     * @param string $value
     * @return null
     */
    protected function parseArray(&$query, $key, $value) {
        $arrayValue = $query[$key] ?? [];
        if (!is_array($arrayValue)) {
            $arrayValue = [$arrayValue];
        }

        $arrayValue[] = $value;
        $query[$key] = $arrayValue;
        return null;
    }

    /**
     * @param array $query
     * @param string $key
     * @param string $value
     * @return null
     */
    protected function parseValue(&$query, $key, $value) {
        $query[$key] = $value;
        return null;
    }


    protected function getKnownParameters() {
        $result = [];

        foreach ($this->query as $key => $value) {
            if (isset($this->parameter_definitions[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}