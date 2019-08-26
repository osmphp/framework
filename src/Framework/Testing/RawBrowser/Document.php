<?php

namespace Osm\Framework\Testing\RawBrowser;

use Osm\Core\App;
use Osm\Framework\Testing\Browser\Document as BaseDocument;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property Browser $parent
 * @property string $html @required
 * @property Crawler $crawler @required
 */
class Document extends BaseDocument
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'crawler': return $m_app->createRaw(Crawler::class, $this->html);
        }
        return parent::default($property);
    }

    /**
     * @param $selector
     * @return Elements
     */
    public function find($selector) {
        return Elements::new(['crawler' => $this->crawler->filter($selector)], null, $this);
    }
}