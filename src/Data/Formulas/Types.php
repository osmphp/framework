<?php

namespace Osm\Data\Formulas;

use Osm\Data\Formulas\Exceptions\InvalidCast;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Data\Formulas\Parser\Token;
use Osm\Core\Object_;

class Types extends Object_
{
    const BOOL_ = 'bool';
    const INT_ = 'int';
    const FLOAT_ = 'float';
    const STRING_ = 'string';
    const DATETIME = 'datetime';
    const BINARY = 'binary';
    const NULL_ = 'null';
    const ANY = 'any';

    public $cast = [
        self::NULL_ => [self::BOOL_, self::INT_, self::FLOAT_, self::STRING_, self::DATETIME],
        self::INT_ => [self::FLOAT_, self::STRING_],
        self::FLOAT_ => [self::STRING_],
    ];

    public $bool_ = [self::NULL_, self::BOOL_];
    public $string_ = [self::NULL_, self::STRING_];
    public $int_ = [self::NULL_, self::INT_];
    public $numeric = [self::NULL_, self::INT_, self::FLOAT_];
    public $numericOrBool = [self::NULL_, self::BOOL_, self::INT_, self::FLOAT_];
    public $numericOrString = [self::NULL_, self::INT_, self::FLOAT_, self::STRING_];
    public $any = [self::NULL_, self::BOOL_, self::INT_, self::FLOAT_, self::STRING_, self::DATETIME, self::BINARY];

    public $literals = [
        Token::STRING_ => self::STRING_,
        Token::INT_ => self::INT_,
        Token::FLOAT_ => self::FLOAT_,
        Token::HEXADECIMAL => self::INT_,
        Token::BINARY => self::INT_,
        Token::TRUE_ => self::BOOL_,
        Token::FALSE_ => self::BOOL_,
        Token::NULL_ => self::NULL_,
    ];

    /**
     * @param Formula $formula
     * @param string $type
     * @return Formula
     */
    public function cast($formula, $type) {
        if (!$formula->data_type) {
            $formula->data_type = $type;
            return $formula;
        }

        if ($formula->data_type === $type) {
            return $formula;
        }

        if (!isset($this->cast[$formula->data_type]) || !in_array($type, $this->cast[$formula->data_type])) {
            throw new InvalidCast(osm_t("Can't cast ':from' to ':to'",
                ['from' => $formula->data_type, 'to' => $type]), $formula->formula, $formula->pos, $formula->length);
        }

        $result = Formulas\Cast::new(['expr' => $formula, 'data_type' => $type], null, $formula->parent);
        $formula->parent = $result;

        return $result;
    }

}