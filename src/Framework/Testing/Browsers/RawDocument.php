<?php

namespace Manadev\Framework\Testing\Browsers;

use Manadev\Core\App;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property RawBrowser $parent
 * @property string $html @required
 * @property Crawler $crawler @required
 */
class RawDocument extends Document
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
        return RawElements::new(['crawler' => $this->crawler->filter($selector)], null, $this);
    }
}