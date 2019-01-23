<?php

namespace Manadev\Framework\Http;

use Manadev\Framework\Data\AdviceRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Response around(callable $callback)
 */
class Advices extends AdviceRegistry
{
    public $class_ = Advice::class;
}