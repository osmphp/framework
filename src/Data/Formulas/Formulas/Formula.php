<?php

namespace Manadev\Data\Formulas\Formulas;

use Manadev\Core\Object_;

/**
 * @property Formula|mixed $parent
 * @property string $type @required @part
 * @property string $formula @required @part
 * @property int $pos @required @part
 * @property int $length @required @part
 *
 * Resolved properties:
 *
 * @property string $data_type @part
 */
class Formula extends Object_
{
    const SORT_EXPR = 'sort_expr';              // SortExpr
    const SELECT_EXPR = 'select_expr';          // SelectExpr
    const EXPR = 'ternary';                     // synonym for TERNARY
    const IDENTIFIER = 'identifier';            // Identifier
    const LOGICAL_OR = 'logical_or';            // Operator
    const LOGICAL_XOR = 'logical_xor';          // Operator
    const LOGICAL_AND = 'logical_and';          // Operator
    const LOGICAL_NOT = 'logical_not';          // Unary
    const IS_NULL = 'is_null';                  // Unary
    const IS_NOT_NULL = 'is_not_null';          // Unary
    const EQUAL = 'equal';                      // Operator
    const EQUAL_OR_GREATER = 'equal_or_greater';// Operator
    const GREATER = 'greater';                  // Operator
    const EQUAL_OR_LESS = 'equal_or_less';      // Operator
    const LESS = 'less';                        // Operator
    const NOT_EQUAL = 'not_equal';              // Operator
    const EQUAL_OR_NULL = 'equal_or_null';      // Operator
    const BIT_OR = 'bit_or';                    // Operator
    const NOT_IN = 'not_in';                    // In_
    const IN_ = 'in';                           // In_
    const NOT_BETWEEN = 'not_between';          // Between
    const BETWEEN = 'between';                  // Between
    const NOT_LIKE = 'not_like';                // Pattern
    const LIKE = 'like';                        // Pattern
    const NOT_REGEXP = 'not_regexp';            // Pattern
    const REGEXP = 'regexp';                    // Pattern
    const BIT_AND = 'bit_and';                  // Operator
    const BIT_SHIFT = 'bit_shift';              // Operator
    const ADD = 'add';                          // Operator
    const MULTIPLY = 'multiply';                // Operator
    const BIT_XOR = 'bit_xor';                  // Operator
    const POSITIVE = 'positive';                // Unary
    const NEGATIVE = 'negative';                // Unary
    const BIT_INVERT = 'bit_invert';            // Unary
    const PARAMETER = 'parameter';              // Parameter
    const CALL = 'call';                        // Call
    const LITERAL = 'literal';                  // Literal
    const COALESCE = 'coalesce';                // Operator
    const TERNARY = 'ternary';                  // Ternary
    const CAST = 'cast';                        // Cast

    // formula types only used internally inside parser
    const SIGNED_SIMPLE = self::POSITIVE;

    public function __toString() {
        if (isset($this->length)) {
            // most formula objects are created during parsing and have all three properties: formula, pos, length
            return mb_substr($this->formula, $this->pos, $this->length);
        }

        // some formula objects are hand-made, those must have at least formula property
        return $this->formula;
    }
}