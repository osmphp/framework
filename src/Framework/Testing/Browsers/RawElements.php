<?php

namespace Manadev\Framework\Testing\Browsers;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @property RawDocument $parent
 * @property Crawler $crawler @required
 */
class RawElements extends Elements
{
    /**
     * @return string
     */
    public function text() {
        return $this->crawler->text();
    }
}