<?php

namespace Manadev\Data\Formulas\Exceptions;

class Error extends \Exception
{
    protected $actualMessage;
    protected $formula;
    protected $pos;
    protected $length;

    public function __construct($actualMessage, $formula, $pos, $length) {
        $this->actualMessage = $actualMessage;
        $this->formula = $formula;
        $this->pos = $pos;
        $this->length = $length;

        $message = $formula
            ? "$actualMessage\n$formula\n" . str_repeat(' ', $pos) . str_repeat('-', $length)
            : $actualMessage;
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getActualMessage() {
        return $this->actualMessage;
    }

    /**
     * @return string
     */
    public function getFormula() {
        return $this->formula;
    }

    /**
     * @return int
     */
    public function getPos() {
        return $this->pos;
    }

    /**
     * @return int
     */
    public function getLength() {
        return $this->length;
    }
}