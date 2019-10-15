<?php

namespace Osm\Data\Formulas\Parser;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Profiler;
use Osm\Data\Formulas\Exceptions\MissingParameter;
use Osm\Data\Formulas\Exceptions\RedundantParameters;
use Osm\Data\Formulas\Exceptions\SyntaxError;
use Osm\Data\Formulas\Formulas;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Data\Formulas\Handlers\ParameterFiller;
use Osm\Data\Formulas\Types;
use Osm\Framework\Cache\Cache;
use Osm\Core\Object_;

/**
 * @property string $text @temp
 * @property int $length @temp
 * @property array $characters @temp
 *
 * @property int $pos @temp
 * @property int $previous_pos @temp
 * @property Token $token @temp
 *
 * @property int $parameter_index @temp
 *
 * @property Scanner $scanner @required
 * @property ParameterFiller $parameter_filler @required
 * @property Cache $cache @required
 * @property Types $types @required
 * @property array $operators @required
 * @property string[] $comparison_operators @required
 * @property string[] $signs @required
 */
class Parser extends Object_
{
    const IDENTIFIER_PATTERN = '[_a-zA-Z][_a-zA-Z0-9]*';
    const FORMULA_PATTERN = '/^(?<identifier>' . self::IDENTIFIER_PATTERN . ')' .
        '(?:' .
            '(?:\s+AS\s+(?<alias>' . self::IDENTIFIER_PATTERN . '))|' .
            '(?:\s+(?<direction>ASC|DESC))' .
        ')?$/u';
    protected $regex_parser_cache = [];

    public $regex_literals = [
        'null' => Token::NULL_,
        'true' => Token::TRUE_,
        'false' => Token::FALSE_,
    ];


    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'scanner': return Scanner::new([], null, $this);
            case 'cache': return $osm_app->cache;
            case 'types': return $osm_app[Types::class];
            case 'parameter_filler': return $osm_app[ParameterFiller::class];
            case 'operators': return [
                Formula::LOGICAL_OR => [
                    'tokens' => [Token::OR_ => true],
                    'operand' => Formula::LOGICAL_XOR,
                ],
                Formula::LOGICAL_XOR => [
                    'tokens' => [Token::XOR_ => true],
                    'operand' => Formula::LOGICAL_AND,
                ],
                Formula::LOGICAL_AND => [
                    'tokens' => [Token::AND_ => true],
                    'operand' => Formula::LOGICAL_NOT,
                ],
                Formula::COALESCE => [
                    'tokens' => [Token::DOUBLE_QUESTION => true],
                    'operand' => Formula::BIT_OR,
                ],
                Formula::BIT_OR => [
                    'tokens' => [Token::PIPE => true],
                    'operand' => Formula::BIT_AND,
                ],
                Formula::BIT_AND => [
                    'tokens' => [Token::AMPERSAND => true],
                    'operand' => Formula::BIT_SHIFT,
                ],
                Formula::BIT_SHIFT => [
                    'tokens' => [Token::DOUBLE_LT => true, Token::DOUBLE_GT => true],
                    'operand' => Formula::ADD,
                ],
                Formula::ADD => [
                    'tokens' => [Token::PLUS => true, Token::MINUS => true],
                    'operand' => Formula::MULTIPLY,
                ],
                Formula::MULTIPLY => [
                    'tokens' => [Token::ASTERISK => true, Token::SLASH => true,
                        Token::DIV => true, Token::MOD => true, Token::PERCENT => true],
                    'operand' => Formula::BIT_XOR,
                ],
                Formula::BIT_XOR => [
                    'tokens' => [Token::HAT => true],
                    'operand' => Formula::SIGNED_SIMPLE,
                ],
            ];
            case 'comparison_operators': return [
                Token::EQ => Formula::EQUAL,
                Token::GT_EQ => Formula::EQUAL_OR_GREATER,
                Token::GT => Formula::GREATER,
                Token::LT_EQ => Formula::EQUAL_OR_LESS,
                Token::LT => Formula::LESS,
                Token::LT_GT => Formula::NOT_EQUAL,
                Token::NOT_EQ => Formula::NOT_EQUAL,
                Token::QUESTION_EQ => Formula::EQUAL_OR_NULL,
            ];
            case 'signs': return [
                Token::PLUS => Formula::POSITIVE,
                Token::MINUS => Formula::NEGATIVE,
                Token::TILDE => Formula::BIT_INVERT,
            ];
        }

        return parent::default($property);
    }

    /**
     * @param string[] $formulas
     * @param array $parameters
     * @param string $as
     * @return Formula[]
     */
    public function parseFormulas($formulas, $parameters, $as = Formula::EXPR) {
        $result = [];

        foreach ($formulas as $formula) {
            $result[] = $this->parse($formula, $parameters, $as);
        }

        if (count($parameters) > 0) {
            throw new RedundantParameters(osm_t(":count redundant parameter(s) passed into formula ':formula'",
                ['count' => count($parameters), 'formula' => $this->text]));
        }

        return $result;
    }

    /**
     * @param string $formula
     * @param array $parameters
     * @param string $as
     * @return Formula
     */
    public function parseFormula($formula, $parameters, $as = Formula::EXPR) {
        $result = $this->parse($formula, $parameters, $as);

        if (count($parameters) > 0) {
            throw new RedundantParameters(osm_t(":count redundant parameter(s) passed into formula ':formula'",
                ['count' => count($parameters), 'formula' => $this->text]));
        }

        return $result;
    }

    protected function parse($text, &$parameters, $as) {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'formulas');
        try {
            if ($result = $this->parseUsingRegex($text, $as)) {
                return $result;
            }

            /* @var CacheItem $cacheItem */
            $this->text = $text;
            $this->characters = preg_split('//u', $text, null, PREG_SPLIT_NO_EMPTY);
            $this->length = mb_strlen($text);
            $this->pos = 0;
            $this->previous_pos = null;
            $this->token = Token::new();
            $this->parameter_index = 0;

            $this->scanner->scan();

            /* @var Formula $formula */
            $formula = $this->parseAs($as);

            if ($this->token->type != Token::EOF) {
                throw $this->syntaxError(osm_t("Expected end of formula, but ':token' found",
                    ['token' => $this->token->text]));
            }

            $cacheItem = CacheItem::new([
                    'formula' => $formula,
                    'parameter_count' => $this->parameter_index,
                ]);

            $formula->parent = $cacheItem;

            if (count($parameters) < $cacheItem->parameter_count) {
                throw new MissingParameter(osm_t(
                    "At least one more parameter should be passed into formula ':formula'",
                    ['formula' => $this->text]));
            }
            $this->parameter_filler->fill($cacheItem->formula, $parameters);
            $parameters = array_slice($parameters, $cacheItem->parameter_count);
            return $cacheItem->formula;
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }
    }

    /**
     * @see \Osm\Data\Formulas\Formulas\Formula::$type @handler
     * @param $type
     * @return Formula
     */
    protected function parseAs($type) {
        switch ($type) {
            case Formula::SORT_EXPR: return $this->parseSortExpr();
            case Formula::SELECT_EXPR: return $this->parseSelectExpr();

            case Formula::IDENTIFIER:
            case Formula::PARAMETER:
            case Formula::CALL:
                return $this->parseSimple();

            case Formula::LOGICAL_OR:
            case Formula::LOGICAL_XOR:
            case Formula::LOGICAL_AND:
            case Formula::BIT_OR:
            case Formula::BIT_AND:
            case Formula::BIT_SHIFT:
            case Formula::ADD:
            case Formula::MULTIPLY:
            case Formula::BIT_XOR:
            case Formula::COALESCE:
                return $this->parseOperator($type);

            case Formula::LOGICAL_NOT:
                return $this->parseLogicalNot();

            case Formula::IS_NULL:
            case Formula::IS_NOT_NULL:
            case Formula::EQUAL:
            case Formula::EQUAL_OR_GREATER:
            case Formula::GREATER:
            case Formula::EQUAL_OR_LESS:
            case Formula::LESS:
            case Formula::NOT_EQUAL:
            case Formula::EQUAL_OR_NULL:
                return $this->parseBooleanPrimary();

            case Formula::NOT_IN:
            case Formula::IN_:
            case Formula::NOT_BETWEEN:
            case Formula::BETWEEN:
            case Formula::NOT_LIKE:
            case Formula::LIKE:
            case Formula::NOT_REGEXP:
            case Formula::REGEXP:
                return $this->parsePredicate();

            case Formula::POSITIVE:
            case Formula::NEGATIVE:
            case Formula::BIT_INVERT:
                return $this->parseSignedSimple();

            case Formula::LITERAL: return $this->parseLiteral();
            case Formula::TERNARY: return $this->parseTernary();
            default: throw new NotSupported(osm_t("Formula type ':type' not supported", compact('type')));
        }
    }

    /**
     * select_expr ::= ternary [AS identifier]
     *
     * @return Formula
     */
    protected function parseSelectExpr() {
        $pos = $this->token->pos;
        $formula = $this->text;

        $expr = $this->parseTernary();
        if ($this->token->type != Token::AS_) {
            return $expr;
        }

        $this->scanner->scan();
        $this->expect(Token::IDENTIFIER);
        $alias = $this->token->text;

        $this->scanner->scan();
        $length = $this->previous_pos - $pos;

        $result = Formulas\SelectExpr::new(compact('expr', 'alias', 'pos', 'formula', 'length'));
        $expr->parent = $result;

        return $result;
    }

    /**
     * sort_expr ::= ternary [ASC | DESC]
     *
     * @return Formula
     */
    protected function parseSortExpr() {
        $pos = $this->token->pos;
        $formula = $this->text;

        $expr = $this->parseTernary();

        $ascending = true;
        switch ($this->token->type) {
            case Token::ASC:
                $this->scanner->scan();
                break;
            case Token::DESC:
                $ascending = false;
                $this->scanner->scan();
                break;
            default:
                return $expr;
        }

        $length = $this->previous_pos - $pos;

        $result = Formulas\SortExpr::new(compact('expr', 'ascending', 'pos', 'formula', 'length'));
        $expr->parent = $result;

        return $result;
    }

    /**
     * ternary ::= logical_or [ ? logical_or : logical_or ]
     *
     * @return Formula
     */
    protected function parseTernary() {
        $pos = $this->token->pos;
        $formula = $this->text;

        $condition = $this->parseOperator(Formula::LOGICAL_OR);

        if ($this->token->type != Token::QUESTION) {
            return $condition;
        }

        $this->scanner->scan();
        $then = $this->parseOperator(Formula::LOGICAL_OR);
        $this->expect(Token::COLON);
        $this->scanner->scan();
        $else_ = $this->parseOperator(Formula::LOGICAL_OR);
        $length = $this->previous_pos - $pos;

        $result = Formulas\Ternary::new(compact('condition', 'then', 'else_', 'pos', 'formula', 'length'));
        $condition->parent = $result;
        $then->parent = $result;
        $else_->parent = $result;

        return $result;

    }

    /**
     * logical_or ::= logical_xor {OR logical_xor}
     * logical_xor ::= logical_and {XOR logical_and}
     * logical_and ::= logical_not {AND logical_not}
     * coalesce ::= bit_or {| bit_or}
     * bit_or ::= bit_and {| bit_and}
     * bit_and ::= bit_shift {| bit_shift}
     * bit_shift ::= add {( << | >> ) add}
     * add ::= multiply {( + | - ) multiply}
     * multiply ::= bit_xor {( * | / | DIV | MOD | % ) bit_xor}
     * bit_xor ::= signed_simple {^ signed_simple}
     *
     * @param string $type
     * @return Formula
     */
    protected function parseOperator($type) {
        $pos = $this->token->pos;
        $formula = $this->text;

        /* @var Formula[] $operands */
        $operator = $this->operators[$type];
        $operands = [$this->parseAs($operator['operand'])];
        $operators = [];

        while (isset($operator['tokens'][$this->token->type])) {
            $operators[] = $this->token->type;
            $this->scanner->scan();
            $operands[] = $this->parseAs($operator['operand']);
        }

        if (count($operands) == 1) {
            return $operands[0];
        }

        $length = $this->previous_pos - $pos;
        $result = Formulas\Operator::new(compact('type', 'operands', 'operators', 'pos', 'formula', 'length'));
        foreach ($operands as $operand) {
            $operand->parent = $result;
        }

        return $result;
    }

    /**
     * logical_not ::= NOT boolean_primary
     *
     * @return Formula
     */
    protected function parseLogicalNot() {
        $pos = $this->token->pos;
        $formula = $this->text;

        if ($this->token->type != Token::NOT) {
            return $this->parseBooleanPrimary();
        }

        $this->scanner->scan();
        $type = Formula::LOGICAL_NOT;
        /* @var Formula $operand */
        $operand = $this->parseBooleanPrimary();
        $length = $this->previous_pos - $pos;
        $result = Formulas\Unary::new(compact('type', 'operand', 'pos', 'formula', 'length'));
        $operand->parent = $result;
        return $result;
    }

    /**
     * boolean_primary ::=
     *      predicate IS [NOT] NULL |
     *      predicate ( = | >= | > | <= | < | <> | != ) predicate |
     *      predicate
     *
     * @return Formula
     */
    protected function parseBooleanPrimary() {
        $pos = $this->token->pos;
        $formula = $this->text;

        $operand = $this->parsePredicate();

        if ($this->token->type == Token::IS_) {
            $type = Formula::IS_NULL;
            $this->scanner->scan();

            if ($this->token->type == Token::NOT) {
                $type = Formula::IS_NOT_NULL;
                $this->scanner->scan();
            }

            $this->expect(Token::NULL_);
            $this->scanner->scan();

            $length = $this->previous_pos - $pos;
            $result = Formulas\Unary::new(compact('type', 'operand', 'pos', 'formula', 'length'));
            $operand->parent = $result;
            return $result;
        }

        if (isset($this->comparison_operators[$this->token->type])) {
            $type = $this->comparison_operators[$this->token->type];
            $operators = [$this->token->type];
            /* @var Formula[] $operands */
            $operands = [$operand];

            $this->scanner->scan();
            $operands[] = $this->parsePredicate();
            $length = $this->previous_pos - $pos;
            $result = Formulas\Operator::new(compact('type', 'operands', 'operators', 'pos',
                'formula', 'length'));
            foreach ($operands as $operand) {
                $operand->parent = $result;
            }
            return $result;
        }

        return $operand;
    }

    /**
     * predicate ::=
     *      coalesce [NOT] IN (simple {, simple}) |
     *      coalesce [NOT] BETWEEN coalesce AND coalesce |
     *      coalesce [NOT] LIKE simple |
     *      coalesce [NOT] REGEXP coalesce |
     *      coalesce
     *
     * @return Formula
     */
    protected function parsePredicate() {
        $pos = $this->token->pos;
        $formula = $this->text;

        $value = $this->parseOperator(Formula::COALESCE);

        $isNegated = false;

        if ($this->token->type == Token::NOT) {
            $isNegated = true;
            $this->scanner->scan();
        }

        if ($this->token->type == Token::IN_) {
            $type = $isNegated ? Formula::NOT_IN : Formula::IN_;
            $this->scanner->scan();
            $this->expect(Token::OPEN_PAR);
            $this->scanner->scan();

            /* @var Formula[] $items */
            $items = [$this->parseSimple()];

            while ($this->token->type == Token::COMMA) {
                $this->scanner->scan();
                $items[] = $this->parseSimple();
            }

            $this->expect(Token::CLOSE_PAR);
            $this->scanner->scan();
            $length = $this->previous_pos - $pos;
            $result = Formulas\In_::new(compact('type', 'value', 'items', 'pos', 'formula', 'length'));
            $value->parent = $result;
            foreach ($items as $item) {
                $item->parent = $result;
            }

            return $result;
        }

        if ($this->token->type == Token::BETWEEN) {
            $type = $isNegated ? Formula::NOT_BETWEEN : Formula::BETWEEN;
            $this->scanner->scan();
            $from = $this->parseOperator(Formula::COALESCE);

            $this->expect(Token::AND_);
            $this->scanner->scan();

            $to = $this->parseOperator(Formula::COALESCE);
            $length = $this->previous_pos - $pos;

            $result = Formulas\Between::new(compact('type', 'value', 'from', 'to', 'pos', 'formula',
                'length'));
            $value->parent = $result;
            $from->parent = $result;
            $to->parent = $result;
            return $result;
        }

        if ($this->token->type == Token::LIKE) {
            $type = $isNegated ? Formula::NOT_LIKE : Formula::LIKE;
            $this->scanner->scan();
            $pattern = $this->parseSignedSimple();
            $length = $this->previous_pos - $pos;
            $result = Formulas\Pattern::new(compact('type', 'value', 'pattern', 'pos', 'formula', 'length'));
            $value->parent = $result;
            $pattern->parent = $value;
            return $result;
        }

        if ($this->token->type == Token::REGEXP) {
            $type = $isNegated ? Formula::NOT_REGEXP : Formula::REGEXP;
            $this->scanner->scan();
            $pattern = $this->parseOperator(Formula::COALESCE);
            $length = $this->previous_pos - $pos;
            $result = Formulas\Pattern::new(compact('type', 'value', 'pattern', 'pos', 'formula', 'length'));
            $value->parent = $result;
            $pattern->parent = $value;
            return $result;
        }

        if ($isNegated) {
            throw $this->syntaxError(osm_t(":token1, :token2, :token3 or :token4 expected", [
                'token1' => Token::getTitle(Token::IN_),
                'token2' => Token::getTitle(Token::BETWEEN),
                'token3' => Token::getTitle(Token::LIKE),
                'token4' => Token::getTitle(Token::REGEXP),
            ]));
        }

        return $value;
    }

    /**
     * signed_simple ::= [ + | - | ~ | ! ] simple
     *
     * @return Formula
     */
    protected function parseSignedSimple() {
        $pos = $this->token->pos;
        $formula = $this->text;

        if (!isset($this->signs[$this->token->type])) {
            return $this->parseSimple();
        }

        $type = $this->signs[$this->token->type];
        $this->scanner->scan();
        $operand = $this->parseSimple();
        $length = $this->previous_pos - $pos;
        $result = Formulas\Unary::new(compact('type', 'operand', 'pos', 'formula', 'length'));
        $operand->parent = $result;
        return $result;
    }

    /**
     * simple ::=
     *      ( ternary ) |
     *      ? |
     *      identifier (expr {, expr}) |
     *      identifier {. identifier} |
     *      literal
     *
     * @return Formula
     */
    protected function parseSimple() {
        $pos = $this->token->pos;
        $formula = $this->text;

        if ($this->token->type == Token::OPEN_PAR) {
            $this->scanner->scan();
            $expr = $this->parseTernary();
            $this->expect(Token::CLOSE_PAR);
            $this->scanner->scan();
            return $expr;
        }

        if ($this->token->type == Token::QUESTION) {
            $this->scanner->scan();

            $index = $this->parameter_index++;

            $length = $this->previous_pos - $pos;
            return Formulas\Parameter::new(compact('pos', 'formula', 'index', 'length'));
        }

        if ($this->token->type == Token::IDENTIFIER) {
            $name = $this->token->text;
            $this->scanner->scan();
            if ($this->token->type == Token::OPEN_PAR) {
                $this->scanner->scan();
                /* @var Formula[] $args */
                if ($this->token->type != Token::CLOSE_PAR) {
                    $args = [$this->parseTernary()];
                    while ($this->token->type == Token::COMMA) {
                        $this->scanner->scan();
                        $args[] = $this->parseTernary();
                    }
                }
                else {
                    $args = [];
                }
                $this->expect(Token::CLOSE_PAR);
                $this->scanner->scan();
                $length = $this->previous_pos - $pos;
                $function = $name;
                $result = Formulas\Call::new(compact('function', 'args', 'pos', 'formula', 'length'));
                foreach ($args as $arg) {
                    $arg->parent = $result;
                }
                return $result;
            }

            $parts = [$name];
            while ($this->token->type == Token::DOT) {
                $this->scanner->scan();
                $this->expect(Token::IDENTIFIER);
                $parts[] = $this->token->text;
                $this->scanner->scan();
            }
            $length = $this->previous_pos - $pos;
            return Formulas\Identifier::new(compact('parts', 'pos', 'formula', 'length'));
        }

        return $this->parseLiteral("Identifier or literal expected");
    }

    /**
     * literal ::=
     *      string  |
     *      numeric |
     *      hexadecimal |
     *      binary |
     *      TRUE |
     *      FALSE |
     *      NULL
     *
     * @param string $error
     * @return Formula
     */
    protected function parseLiteral($error = "Literal expected") {
        $pos = $this->token->pos;
        $formula = $this->text;

        if (isset($this->types->literals[$this->token->type])) {
            $token = $this->token->type;
            $value = $this->token->text;
            $this->scanner->scan();
            $length = $this->previous_pos - $pos;
            return Formulas\Literal::new(compact('value', 'pos', 'formula', 'token', 'length'));
        }

        throw $this->syntaxError(osm_t($error));
    }

    /**
     * @param int $tokenType
     * @throws SyntaxError
     */
    protected function expect($tokenType) {
        if ($this->token->type != $tokenType) {
            throw $this->syntaxError(osm_t(":token expected", ['token' => Token::getTitle($tokenType)]));
        }
    }

    protected function syntaxError($message) {
        return new SyntaxError($message, $this->text, $this->token->pos, $this->pos - $this->token->pos);
    }

    protected function parseUsingRegex($text, $as) {
        if (!isset($this->regex_parser_cache[$text])) {
            $this->regex_parser_cache[$text] =
                preg_match(static::FORMULA_PATTERN, $text, $match, PREG_OFFSET_CAPTURE)
                    ? $match : false;
        }
        if (!($match = $this->regex_parser_cache[$text])) {
            return null;
        }

        if (isset($match['direction'])) {
            if ($as != Formula::SORT_EXPR) {
                return null;
            }

            return Formulas\SortExpr::new([
                'expr' => $this->regexIdentifier($match['identifier'][0],
                    $match['identifier'][1], $text),
                'ascending' => $match['direction'][0] == 'ASC',
                'pos' => 0,
                'formula' => $text,
                'length' => mb_strlen($text),
            ]);
        }

        if (isset($match['alias'])) {
            if ($as != Formula::SELECT_EXPR) {
                return null;
            }

            return Formulas\SelectExpr::new([
                'expr' => $this->regexIdentifier($match['identifier'][0],
                    $match['identifier'][1], $text),
                'alias' => $match['alias'][0],
                'pos' => 0,
                'formula' => $text,
                'length' => mb_strlen($text),
            ]);
        }

        return $this->regexIdentifier($text,0, $text);
    }

    protected function regexIdentifier($identifier, $pos, $formula) {
        $literal = mb_strtolower($identifier);
        if (isset($this->regex_literals[$literal])) {
            return Formulas\Literal::new([
                'value' => $literal,
                'pos' => $pos,
                'formula' => $formula,
                'token' => $this->regex_literals[$literal],
                'length' => mb_strlen($literal),
            ]);
        }

        return Formulas\Identifier::new([
            'parts' => [$identifier],
            'pos' => $pos,
            'formula' => $formula,
            'length' => mb_strlen($identifier),
        ]);
    }
}