<?php

namespace Manadev\Framework\Views;

use Manadev\Core\Object_;

/**
 * @property string $base_url @required @part
 * @property array $transient_query @required @part
 *
 * @see \Manadev\Ui\SnackBars\Module:
 *      @property int $close_snack_bars_after @required @part
 */
class JsConfig extends Object_
{
    /**
     * @required @part
     * @var string[]
     */
    public $translations = [];

    public function translate($text) {
        $this->translations[$text] = (string)m_($text);
    }
}