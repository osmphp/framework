<?php

namespace Osm\Framework\Emails\Views;

use Osm\Framework\Views\View;

/**
 * @property string $subject @required @part
 * @property string|array $from @required @part
 * @property string|array $to @required @part
 * @property View $body @required @part
 * @property string $body_ @required
 * @property string $plain @required
 */
class Email extends View
{
    protected function default($property) {
        switch ($property) {
            case 'from': return env('SMTP_FROM', env('SMTP_USER'));
            case 'body_': return (string)$this->body;
            case 'plain': return $this->getPlain();
        }

        return parent::default($property);
    }

    protected function getPlain() {
        return $this->stripParagraphs($this->stripLinks(
            strip_tags($this->body_, '<p><a>')));
    }

    protected function stripParagraphs($html) {
        return preg_replace_callback('/\s*<\s*p[^>]*>(?<text>[^<]*)<\s*\/p[^>]*>/u', function($match) {
            return "{$match['text']}\n\n";
        }, $html);
    }

    protected function stripLinks($html) {
        return preg_replace_callback('/<\s*a(?<attributes>[^>]*)>(?:[^<]*?)<\s*\/\s*a>/u', function($match) {
            if (preg_match('/href="(?<url>[^"]+)"/u', $match['attributes'], $attributeMatch)) {
                return $attributeMatch['url'];
            }
            else {
                return '';
            }
        }, $html);
    }
}