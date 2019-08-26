<?php

namespace Osm\Data\Formulas\Parser;

use Osm\Data\Formulas\Exceptions\UnexpectedCharacter;
use Osm\Data\Formulas\Exceptions\UnexpectedEOF;
use Osm\Core\Object_;

/**
 * @property Parser $parent
 */
class Scanner extends Object_
{
    const STATE_INITIAL = 0;
    const STATE_GT = 1;
    const STATE_LT = 2;
    const STATE_EXCLAMATION = 3;
    const STATE_QUESTION = 4;
    const STATE_IDENTIFIER = 5;
    const STATE_NUMERIC = 6;
    const STATE_HEXADECIMAL = 7;
    const STATE_BINARY = 8;
    const STATE_SINGLE_QUOTED_STRING = 9;
    const STATE_ESCAPE_SINGLE_QUOTED_STRING = 10;

    public $identifier_starting_char = '_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public $identifier_char = '_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    public $numeric_starting_char = '0123456789';
    public $numeric_and_dot_char = '0123456789.';
    public $numeric_char = '0123456789';
    public $hexadecimal_char = 'abcdefABCDEF0123456789';
    public $binary_char = '01';
    public $escape_char = '0\'"bnrtZ\\%_';

    public function scan() {
        $state = static::STATE_INITIAL;
        $parser = $this->parent;
        $token = $parser->token;
        $pos = $parser->previous_pos = $token->pos = $parser->pos;
        $type = null;
        
        $hasDot = false;

        while ($parser->pos < $parser->length) {
            $ch = $parser->characters[$parser->pos++];

            switch ($state) {
                case static::STATE_INITIAL:
                    switch ($ch) {
                        case " ":
                        case "\t":
                        case "\n":
                        case "\r":
                        case "\0":
                        case "\x0B":
                            $pos++;
                            $token->pos++;
                            break;
                        case '=': $type = Token::EQ; break;
                        case '>': $state = static::STATE_GT; break;
                        case '<': $state = static::STATE_LT; break;
                        case '!': $state = static::STATE_EXCLAMATION; break;
                        case '(': $type = Token::OPEN_PAR; break;
                        case ')': $type = Token::CLOSE_PAR; break;
                        case ',': $type = Token::COMMA; break;
                        case '|': $type = Token::PIPE; break;
                        case '&': $type = Token::AMPERSAND; break;
                        case '+': $type = Token::PLUS; break;
                        case '-': $type = Token::MINUS; break;
                        case '*': $type = Token::ASTERISK; break;
                        case '/': $type = Token::SLASH; break;
                        case '%': $type = Token::PERCENT; break;
                        case '^': $type = Token::HAT; break;
                        case '~': $type = Token::TILDE; break;
                        case ':': $type = Token::COLON; break;
                        case '.': $type = Token::DOT; break;
                        case '?': $state = static::STATE_QUESTION; break;
                        case "'": $state = static::STATE_SINGLE_QUOTED_STRING; break;
                        default:
                            if (mb_strpos($this->identifier_starting_char, $ch) !== false) {
                                $state = static::STATE_IDENTIFIER;
                            }
                            elseif (mb_strpos($this->numeric_char, $ch) !== false) {
                                $state = static::STATE_NUMERIC;
                            }
                            else {
                                throw $this->unexpectedCharacter();
                            }
                            break;
                    }
                    break;
                case static::STATE_IDENTIFIER:
                    if (mb_strpos($this->identifier_char, $ch) === false) {
                        $type = Token::IDENTIFIER;
                        $parser->pos--;
                    }
                    break;
                case static::STATE_NUMERIC:
                    if ($ch == '.') {
                        if ($hasDot) {
                            throw $this->unexpectedCharacter();
                        }
                        else {
                            $hasDot = true;
                        }
                    }
                    elseif (mb_strpos($this->numeric_char, $ch) === false) {
                        if ($parser->pos - $pos == 2 && $parser->characters[$pos] == '0' &&
                            ($ch == 'x' || $ch == 'b'))
                        {
                            $state = $ch == 'x' ? static::STATE_HEXADECIMAL : static::STATE_BINARY;
                        }
                        else {
                            $type = $hasDot ? Token::FLOAT_ : Token::INT_;
                            $parser->pos--;
                        }
                    }
                    break;
                case static::STATE_HEXADECIMAL:
                    if (mb_strpos($this->hexadecimal_char, $ch) === false) {
                        if ($parser->pos - $pos <= 3) {
                            throw $this->unexpectedCharacter();
                        }
                        else {
                            $type = Token::HEXADECIMAL;
                            $parser->pos--;
                        }
                    }
                    break;
                case static::STATE_BINARY:
                    if (mb_strpos($this->binary_char, $ch) === false) {
                        if ($parser->pos - $pos <= 3) {
                            throw $this->unexpectedCharacter();
                        }
                        else {
                            $type = Token::BINARY;
                            $parser->pos--;
                        }
                    }
                    break;
                case static::STATE_SINGLE_QUOTED_STRING:
                    switch ($ch) {
                        case "'": $type = Token::STRING_; break;
                        case '\\': $state = static::STATE_ESCAPE_SINGLE_QUOTED_STRING; break;
                    }
                    break;
                case static::STATE_ESCAPE_SINGLE_QUOTED_STRING:
                    // any character is acceptable after backslash
                    $state = static::STATE_SINGLE_QUOTED_STRING;
                    break;
                case static::STATE_GT:
                    switch ($ch) {
                        case '=': $type = Token::GT_EQ; break;
                        case '>': $type = Token::DOUBLE_GT; break;
                        default: $type = Token::GT; $parser->pos--; break;
                    }
                    break;
                case static::STATE_LT:
                    switch ($ch) {
                        case '=': $type = Token::LT_EQ; break;
                        case '<': $type = Token::DOUBLE_LT; break;
                        case '>': $type = Token::LT_GT; break;
                        default: $type = Token::LT; $parser->pos--; break;
                    }
                    break;
                case static::STATE_EXCLAMATION:
                    switch ($ch) {
                        case '=': $type = Token::NOT_EQ; break;
                        default:
                            throw $this->unexpectedCharacter();
                    }
                    break;
                case static::STATE_QUESTION:
                    switch ($ch) {
                        case '?': $type = Token::DOUBLE_QUESTION; break;
                        case '=': $type = Token::QUESTION_EQ; break;
                        default: $type = Token::QUESTION; $parser->pos--; break;
                    }
                    break;
            }

            if ($type) {
                break;
            }
        }

        if ($type) {
            $token->type = $type;
        }
        else {
            switch ($state) {
                case static::STATE_INITIAL: $token->type = Token::EOF; break;
                case static::STATE_IDENTIFIER: $token->type = Token::IDENTIFIER; break;
                case static::STATE_GT: $token->type = Token::GT; break;
                case static::STATE_LT: $token->type = Token::LT; break;
                case static::STATE_QUESTION: $token->type = Token::QUESTION; break;
                case static::STATE_NUMERIC:
                    $token->type = $hasDot ? Token::FLOAT_ : Token::INT_;
                    break;
                case static::STATE_HEXADECIMAL:
                    if ($parser->pos - $pos <= 3) {
                        throw $this->unexpectedEndOfFormula();
                    }
                    else {
                        $token->type = Token::HEXADECIMAL;
                    }
                    break;
                case static::STATE_BINARY:
                    if ($parser->pos - $pos <= 3) {
                        throw $this->unexpectedEndOfFormula();
                    }
                    else {
                        $token->type = Token::BINARY;
                    }
                    break;
                default:
                    throw $this->unexpectedEndOfFormula();
            }
        }

        $token->text = mb_substr($parser->text, $pos, $parser->pos - $pos);
        if ($token->type == Token::IDENTIFIER) {
            $token->text = mb_strtolower($token->text);
            if (isset(Token::$reserved_keywords[$token->text])) {
                $token->type = Token::$reserved_keywords[$token->text];
            }
        }
    }

    protected function unexpectedCharacter() {
        return new UnexpectedCharacter(m_("Unexpected character ':ch'",
            ['ch' => $this->parent->characters[$this->parent->pos - 1]]), $this->parent->text,
            $this->parent->pos - 1, 1);
    }

    protected function unexpectedEndOfFormula() {
        return new UnexpectedEOF(m_("Unexpected end of formula"), $this->parent->text,
            $this->parent->length, 1);
    }
}